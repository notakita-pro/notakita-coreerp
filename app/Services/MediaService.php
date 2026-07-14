<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaService
{
    /**
     * HTTP timeout (detik)
     */
    protected int $timeout;

    /**
     * Storage disk
     */
    protected string $disk;

    public function __construct()
    {
        $this->timeout = max(
            5,
            (int) config('media.timeout', 60)
        );

        $this->disk = config('media.disk', 'local');
    }

    /**
     * Download media dari WhatsApp Cloud API.
     *
     * @throws Exception
     */
    public function download(string $mediaId): array
    {
        if (blank($mediaId)) {
            throw new Exception('Media ID kosong.');
        }

        Log::info('[MediaService] Download dimulai', [
            'media_id' => $mediaId,
        ]);

        $media = $this->getMediaInfo($mediaId);

        $url = $media['url'] ?? null;

        if (!$url) {
            throw new Exception('Meta tidak mengembalikan URL media.');
        }

        $mime = $media['mime_type'] ?? 'application/octet-stream';

        $start = microtime(true);

        $response = Http::withToken(config('whatsapp.access_token'))
            ->connectTimeout(5)
            ->timeout($this->timeout)
            ->retry(2, 500)
            ->get($url);

        Log::info('[MediaService] Download response', [
            'status' => $response->status(),
            'elapsed_ms' => round((microtime(true) - $start) * 1000),
        ]);

        if (!$response->successful()) {

            Log::error('[MediaService] Download gagal', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new Exception('Gagal mengunduh file media.');
        }

        $binary = $response->body();

        if ($binary === '') {
            throw new Exception('File media kosong.');
        }

        Log::info('[MediaService] Binary downloaded', [
            'bytes' => strlen($binary),
        ]);

        $path = $this->store(
            $binary,
            $mime
        );

        $this->optimizeImage(
            $path,
            $mime
        );

        return [
            'disk' => $this->disk,
            'path' => $path,
        ];
    }

    /**
     * Mengambil metadata media dari Meta.
     *
     * @throws Exception
     */
    protected function getMediaInfo(string $mediaId): array
    {
        $url = sprintf(
            'https://graph.facebook.com/%s/%s',
            config('whatsapp.graph_version', 'v25.0'),
            $mediaId
        );

        Log::info('[MediaService] Request Media Info', [
            'url' => $url,
        ]);

        $start = microtime(true);

        $response = Http::acceptJson()
            ->withToken(config('whatsapp.access_token'))
            ->connectTimeout(5)
            ->timeout($this->timeout)
            ->retry(2, 500)
            ->get($url);

        Log::info('[MediaService] Media Info response', [
            'status' => $response->status(),
            'elapsed_ms' => round((microtime(true) - $start) * 1000),
        ]);

        if (!$response->successful()) {

            Log::error('[MediaService] Meta Error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new Exception('Meta gagal memberikan informasi media.');
        }

        return $response->json();
    }

    /**
     * Simpan file.
     */
    protected function store(string $binary, string $mime): string
    {
        $folder = config('media.folder', 'receipts')
            . '/'
            . now()->format('Y/m/d');

        $filename = Str::uuid() . '.'
            . $this->extensionFromMime($mime);

        $path = $folder . '/' . $filename;

        $disk = Storage::disk($this->disk);

        $disk->put($path, $binary);

        Log::info('[MediaService] File berhasil disimpan', [
            'disk' => $this->disk,
            'path' => $path,
            'size' => $disk->size($path),
        ]);

        return $path;
    }

    /**
     * Optimasi gambar.
     */
    protected function optimizeImage(
        string $path,
        string $mime
    ): void {

        if (!str_starts_with($mime, 'image/')) {
            return;
        }

        if (!extension_loaded('gd')) {

            Log::warning('[MediaService] GD extension tidak tersedia');

            return;
        }

        $disk = Storage::disk($this->disk);

        $fullPath = $disk->path($path);

        if (!is_file($fullPath)) {
            return;
        }

        $imageInfo = @getimagesize($fullPath);

        if (!$imageInfo) {
            return;
        }

        [$width, $height] = $imageInfo;

        $maxWidth = (int) config('media.max_width', 1600);

        if ($width <= $maxWidth) {

            Log::info('[MediaService] Resize dilewati', [
                'width' => $width,
                'height' => $height,
            ]);

            return;
        }

        $start = microtime(true);

        $beforeSize = filesize($fullPath);

        $ratio = $height / $width;

        $newWidth = $maxWidth;

        $newHeight = (int) round($newWidth * $ratio);

        $src = match ($mime) {

            'image/jpeg' => @imagecreatefromjpeg($fullPath),

            'image/png' => @imagecreatefrompng($fullPath),

            'image/webp' => function_exists('imagecreatefromwebp')
                ? @imagecreatefromwebp($fullPath)
                : null,

            default => null,
        };

        if (!$src) {
            return;
        }

        $dst = imagecreatetruecolor(
            $newWidth,
            $newHeight
        );

        if ($mime === 'image/png') {

            imagealphablending($dst, false);

            imagesavealpha($dst, true);
        }

        imagecopyresampled(
            $dst,
            $src,
            0,
            0,
            0,
            0,
            $newWidth,
            $newHeight,
            $width,
            $height
        );

        match ($mime) {

            'image/jpeg' => imagejpeg(
                $dst,
                $fullPath,
                (int) config('media.jpeg_quality', 82)
            ),

            'image/png' => imagepng(
                $dst,
                $fullPath,
                8
            ),

            'image/webp' => function_exists('imagewebp')
                ? imagewebp(
                    $dst,
                    $fullPath,
                    (int) config('media.jpeg_quality', 82)
                )
                : false,

            default => false,
        };

        imagedestroy($src);

        imagedestroy($dst);

        clearstatcache(true, $fullPath);

        Log::info('[MediaService] Image optimized', [

            'path' => $path,

            'before_kb' => round($beforeSize / 1024, 1),

            'after_kb' => round(filesize($fullPath) / 1024, 1),

            'before_width' => $width,

            'before_height' => $height,

            'after_width' => $newWidth,

            'after_height' => $newHeight,

            'elapsed_ms' => round((microtime(true) - $start) * 1000),

        ]);
    }

    /**
     * MIME -> Extension
     */
    protected function extensionFromMime(string $mime): string
    {
        return match ($mime) {

            'image/jpeg' => 'jpg',

            'image/png' => 'png',

            'image/webp' => 'webp',

            'image/heic' => 'heic',

            'image/heif' => 'heif',

            'application/pdf' => 'pdf',

            default => 'bin',
        };
    }
}