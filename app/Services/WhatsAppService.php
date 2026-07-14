<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected string $phoneNumberId;

    protected string $accessToken;

    public function __construct()
    {
        $this->phoneNumberId = config(
            'whatsapp.phone_number_id'
        );

        $this->accessToken = config(
            'whatsapp.access_token'
        );
    }

    /**
     * ==========================================================================
     * Endpoint Graph API
     * ==========================================================================
     */
    protected function endpoint(): string
    {
        return sprintf(

            '%s/%s/%s/messages',

            rtrim(
                config(
                    'whatsapp.base_url',
                    'https://graph.facebook.com'
                ),
                '/'
            ),

            config(
                'whatsapp.graph_version',
                'v25.0'
            ),

            $this->phoneNumberId

        );
    }

    /**
     * ==========================================================================
     * Text Message
     * ==========================================================================
     */
    public function sendTextMessage(
        string $to,
        string $message
    ) {

        return $this->send([

            'messaging_product' => 'whatsapp',

            'recipient_type' => 'individual',

            'to' => $to,

            'type' => 'text',

            'text' => [

                'preview_url' => false,

                'body' => $message,

            ],

        ]);

    }

    /**
     * ==========================================================================
     * CTA URL
     * ==========================================================================
     */
    public function sendCtaUrl(
        string $to,
        string $body,
        string $buttonText,
        string $url,
        ?string $header = null,
        ?string $footer = null
    ) {

        $interactive = [

            'type' => 'cta_url',

            'body' => [

                'text' => $body,

            ],

            'action' => [

                'name' => 'cta_url',

                'parameters' => [

                    'display_text' => $buttonText,

                    'url' => $url,

                ],

            ],

        ];

        if ($header) {

            $interactive['header'] = [

                'type' => 'text',

                'text' => $header,

            ];

        }

        if ($footer) {

            $interactive['footer'] = [

                'text' => $footer,

            ];

        }

        return $this->send([

            'messaging_product' => 'whatsapp',

            'recipient_type' => 'individual',

            'to' => $to,

            'type' => 'interactive',

            'interactive' => $interactive,

        ]);

    }

    /*
    |--------------------------------------------------------------------------
    | Future Features
    |--------------------------------------------------------------------------
    |
    | sendImage()
    | sendDocument()
    | sendReplyButtons()
    | sendList()
    | sendTemplate()
    |
    */

    /**
     * ==========================================================================
     * HTTP Sender
     * ==========================================================================
     */
    protected function send(
        array $payload
    ) {

        Log::info('[WhatsAppService] REQUEST', [

            'type' => $payload['type'] ?? null,

            'to' => $payload['to'] ?? null,

            'payload_size' => strlen(
                json_encode($payload)
            ),

        ]);

        Log::info('[WhatsAppService] PAYLOAD', [

            'payload' => $payload,

        ]);

        $start = microtime(true);

        try {

            $response = Http::asJson()

                ->acceptJson()

                ->withToken(
                    $this->accessToken
                )

                ->connectTimeout(5)

                ->timeout(
                    config(
                        'whatsapp.timeout',
                        30
                    )
                )

                ->retry(

                    config(
                        'whatsapp.download_retry',
                        2
                    ),

                    500

                )

                ->post(

                    $this->endpoint(),

                    $payload

                );

            Log::info('[WhatsAppService] RESPONSE', [

                'status' => $response->status(),

                'elapsed_ms' => round(
                    (microtime(true) - $start) * 1000
                ),

                'message_id' => $response->json(
                    'messages.0.id'
                ),

            ]);

            if (! $response->successful()) {

                Log::warning('[WhatsAppService] Failed', [

                    'payload' => $payload,

                    'status' => $response->status(),

                    'body' => $response->json(),

                ]);

            }

            return $response;

        } catch (\Throwable $e) {

            Log::error(

                '[WhatsAppService] Exception',

                [

                    'message' => $e->getMessage(),

                    'payload' => $payload,

                    'elapsed_ms' => round(
                        (microtime(true) - $start) * 1000
                    ),

                ]

            );

            throw $e;

        }

    }
}