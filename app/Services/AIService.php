<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key');
    }

    /**
     * Mengirim prompt ke AI
     */
    public function ask(string $prompt): string
    {
        try {

            $response = Http::withToken($this->apiKey)
                ->post(
                    'https://api.openai.com/v1/chat/completions',
                    [
                        'model' => 'gpt-4.1-mini',

                        'messages' => [

                            [
                                'role' => 'system',
                                'content' =>
                                    'Kamu adalah asisten virtual ELBEJE TEKNO. Jawablah dalam Bahasa Indonesia dengan sopan, singkat, dan jelas.'
                            ],

                            [
                                'role' => 'user',
                                'content' => $prompt
                            ]

                        ],

                        'temperature' => 0.7
                    ]
                );

            if (!$response->successful()) {

                Log::error($response->body());

                return "Maaf, AI sedang mengalami gangguan.";
            }

            return $response['choices'][0]['message']['content'];

        } catch (\Throwable $e) {

            Log::error($e->getMessage());

            return "Maaf, terjadi kesalahan saat menghubungi AI.";
        }
    }
}