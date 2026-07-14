<?php

namespace App\Services;

use App\DTO\ReceiptData;
use App\Models\GeminiUsage;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class VisionService
{
    /**
     * Menganalisis gambar nota menggunakan Google Gemini API dengan dukungan Failover & Retry.
     *
     * @param string $path
     * @param string|null $companyPhone
     * @return ReceiptData
     * @throws Exception
     */
    public function analyze(string $path, ?string $companyPhone = null): ReceiptData
    {
        $disk = config('media.disk', 'local');

        if (!Storage::disk($disk)->exists($path)) {
            throw new Exception("File tidak ditemukan: {$path}");
        }

        $fullPath = Storage::disk($disk)->path($path);
        $binary = file_get_contents($fullPath);

        if ($binary === false) {
            throw new Exception("Gagal membaca file: {$fullPath}");
        }

        $mime = $this->detectMime($fullPath);

        Log::info('[VisionService] FILE', [
            'disk' => $disk,
            'path' => $path,
            'size' => filesize($fullPath),
            'mime' => $mime,
        ]);

        Log::info('[VisionService] MODEL', [
            'model' => config('gemini.model'),
        ]);

        $prompt =$this->defaultPrompt();

        $payload = [
            'contents' => [[
                'parts' => [
                    ['text' => $prompt],
                    [
                        'inlineData' => [
                            'mimeType' => $mime,
                            'data'     => base64_encode($binary),
                        ],
                    ],
                ],
            ]],
        ];

        $start = microtime(true);$maxAttempts = config('gemini.retry.max_attempts', 3);

        Log::info('[VisionService] REQUEST', [
            'image_size_kb' => round(filesize($fullPath) / 1024, 1),             'prompt_length' => strlen($prompt),
            'max_attempts'  => $maxAttempts,
        ]);

        $retryDelay = [500000];$response = null;
        $activeProvider = null;

        // Loop 1: Sistem Failover Antar API Keys / Providers
        foreach (config('gemini.keys', []) as $api) {
            $activeProvider =$api['name'];

            $url = sprintf(
                '%s/%s/models/%s:generateContent?key=%s',
                config('gemini.base_url'),
                config('gemini.api_version'),
                config('gemini.model'),
                $api['key']
            );

            Log::info('[VisionService] Using Provider', [
                'provider' => $activeProvider,
            ]);

            $response = null;

            // Loop 2: Sistem Retry Internal untuk Server Error (5xx) atau Network Issue
            for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
                Log::info('[VisionService] Gemini attempt', [
                    'provider' => $activeProvider,
                    'attempt'  => $attempt,
                ]);

                try {
                    $requestStart = microtime(true);

                    $response = Http::connectTimeout(5)
                        ->timeout(config('gemini.timeout', 30))
                        ->post($url,$payload);

                    Log::info('[VisionService] Gemini response', [
                        'provider'   => $activeProvider,
                        'attempt'    => $attempt,
                        'status'     => $response->status(),
                        'elapsed_ms' => round((microtime(true) - $requestStart) * 1000),
                    ]);

                    // KONDISI SUKSES (HTTP 200)
                    if ($response->successful()) {
                        Log::info('[VisionService] Provider Success', [
                            'provider' => $activeProvider,
                        ]);
                        break 2; 
                    }

                    // KONDISI KUOTA HABIS (HTTP 429) -> Langsung ganti API Key berikutnya
                    if ($response->status() === 429) {
                        Log::warning('[VisionService] Quota Habis', [
                            'provider' => $activeProvider,
                        ]);
                        break; 
                    }

                    // KONDISI SERVER ERROR (5xx) -> Lakukan retry pada API Key yang sama
                    if (in_array($response->status(), [500, 502, 503, 504])) {
                        Log::warning('[VisionService] Retry Server Error', [
                            'provider' => $activeProvider,
                            'attempt'  => $attempt,
                            'status'   => $response->status(),
                        ]);
                    } else {
                        // Error Klien Fatal lainnya (e.g., 400 Bad Request) -> Stop pemrosesan
                        break 2;
                    }

                } catch (Throwable $e) {
                    Log::warning('[VisionService] Network Retry', [
                        'provider' => $activeProvider,
                        'attempt'  => $attempt,
                        'message'  => $e->getMessage(),
                    ]);
                }

                // Efek jeda sebelum melakukan retry
                if ($attempt < $maxAttempts) {$delay = ($retryDelay[$attempt - 1] ?? 500000) + random_int(0, 150000);
                    Log::info('[VisionService] Retry delay', [
                        'provider' => $activeProvider,
                        'attempt'  => $attempt,
                        'sleep_ms' => intval($delay / 1000),
                    ]);
                    usleep($delay);
                }
            }

            // Jika karena alasan kuota habis (429), lanjutkan loop untuk mencoba provider selanjutnya
            if ($response &&$response->status() === 429) {
                continue;
            }

            // Jika bukan 429, hentikan pencarian provider
            break;
        }

        // PENANGANAN JIKA SEMUA AKUN / ATTEMPT GAGAL TOTAL
        if (!$response || !$response->successful()) {
            $status = optional($response)->status();

            Log::error('[VisionService] Gemini Error Final', [
                'provider' => $activeProvider,
                'status'   => $status,
                'body'     => optional($response)->body(),
            ]);

            $this->saveUsage([
             
                'provider' => $activeProvider,
                'model'         => config('gemini.model'),
                'company_phone' => $companyPhone,
                'image_size_kb' => round(filesize($fullPath) / 1024),                 'http_status'   =>$status,
                'success'       => false,
                'elapsed_ms'    => round((microtime(true) - $start) * 1000),                 'error_code'    =>$status,
                'error_message' => data_get($response?->json(), 'error.message', 'Unknown API Error'),
            ]);

            switch ($status) {
                case 429:
                    throw new Exception('Quota Gemini habis.');
                case 500:
                case 502:
                case 503:
                case 504:
                    throw new Exception('Gemini sedang sibuk.');
                default:
                    throw new Exception('Gemini API gagal memproses nota.');
            }
        }

        // EKSTRAKSI HASIL KETIKA BERHASIL
        Log::info('[VisionService] Gemini usage metadata', [
            'provider' => $activeProvider,
            'usage'    => $response->json('usageMetadata'),
        ]);

        $candidate = data_get($response->json(), 'candidates.0');
        if (!$candidate) {
            throw new Exception('Gemini tidak mengembalikan candidate konten.');
        }

        $text = data_get($candidate, 'content.parts.0.text');
        if (!$text) {
            throw new Exception('Gemini tidak mengembalikan data teks.');
        }

        Log::info('[VisionService] Gemini text fetched', [
            'provider' => $activeProvider,
            'length'   => strlen($text),
        ]);

        $json = $this->cleanJson($text);
        $usage =$response->json('usageMetadata', []);

        // Log Data Transaksi Sukses ke Tabel GeminiUsage
        $this->saveUsage([

            'provider'  => $activeProvider,
            'model'          => config('gemini.model'),
            'company_phone'  => $companyPhone,
            'supplier'       => $json['supplier'] ?? null,
            'invoice_number' => $json['invoice_number'] ?? null,
            'invoice_total'  => $json['total'] ?? null,
            'image_size_kb'  => round(filesize($fullPath) / 1024),             'prompt_tokens'  => data_get($usage, 'promptTokenCount'),
            'output_tokens'  => data_get($usage, 'candidatesTokenCount'),
            'total_tokens'   => data_get($usage, 'totalTokenCount'),
            'elapsed_ms'     => round((microtime(true) - $start) * 1000),             'http_status'    =>$response->status(),
            'success'        => true,
        ]);

if (!isset($json['items']) || !is_array($json['items'])) {
            throw new Exception('JSON Gemini tidak memiliki array items.');
        }

        Log::info('[VisionService] Receipt summary success', [
            'supplier'   => $json['supplier'] ?? null,
            'items'      => count($json['items']),
            'total'      => $json['total'] ?? null,
            'elapsed_ms' => round((microtime(true) - $start) * 1000),
        ]);

        return ReceiptData::fromArray($json);
    }

    /**
     * Mendeteksi MIME Type berkas.
     */
    private function detectMime(string $fullPath): string
    {
        if (function_exists('mime_content_type')) {
            $mime = @mime_content_type($fullPath);
            if ($mime) {
                return $mime;
            }
        }

        return match (strtolower(pathinfo($fullPath, PATHINFO_EXTENSION))) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png'         => 'image/png',
            'webp'        => 'image/webp',
            default       => 'application/octet-stream',
        };
    }

    /**
     * Membersihkan pembungkus Markdown JSON dari Gemini response.
     */
    private function cleanJson(string $text): array
    {
        $text = trim($text);$text = preg_replace('/^```json/i', '', $text);
        $text = preg_replace('/^```/i', '', $text);$text = preg_replace('/```$/', '', $text);

        $json = json_decode(trim($text), true);

        if (!is_array($json)) {
            Log::error('[VisionService] Invalid JSON format captured', [
                'response' => $text,
            ]);
            throw new Exception('Gemini mengembalikan JSON yang tidak valid.');
        }

        return $json;
    }

    /**
     * Menyimpan log pemakaian token ke database.
     */
    private function saveUsage(array $data): void
    {
        try {
            GeminiUsage::create($data);
        } catch (Throwable $e) {
            Log::warning('[VisionService] Gagal menyimpan GeminiUsage', [
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Menyediakan kerangka Prompt Default Nota OCR.
     */
    private function defaultPrompt(): string
    {
        return <<<'PROMPT'
Ekstrak isi nota menjadi JSON berikut.

{
 "supplier":null,
 "invoice_number":null,
 "date":null,
 "subtotal":null,
 "tax":null,
 "discount":null,
 "total":null,
 "items":[
  {
   "name":"",
   "qty":null,
   "unit_price":null,
   "total":null
  }
 ]
}

Gunakan null jika tidak ada.
Semua nilai numerik harus berupa NUMBER JSON.
Jangan gunakan Rp, spasi, titik pemisah ribuan, atau koma desimal.
Gunakan titik hanya sebagai desimal.
Jangan menambah field.
Balas JSON saja.
PROMPT;
    }
}