<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Services\WhatsAppService;
use App\Jobs\ProcessReceiptJob;
use App\Support\WhatsAppMessages;

class WhatsAppWebhookController extends Controller
{
    public function __construct(
        protected WhatsAppService $whatsapp
    ) {
    }

    /**
     * Endpoint Webhook
     */
    public function handle(Request $request)
    {
        return $request->isMethod('get')
            ? $this->verifyWebhook($request)
            : $this->receiveWebhook($request);
    }

    /**
     * --------------------------------------------------------------------------
     * Verifikasi Meta Webhook
     * --------------------------------------------------------------------------
     */
    private function verifyWebhook(Request $request)
    {
        if (
            $request->query('hub_mode') === 'subscribe'
            && $request->query('hub_verify_token') === config('whatsapp.verify_token')
        ) {
            return response(
                $request->query('hub_challenge'),
                200
            )->header('Content-Type', 'text/plain');
        }

        Log::warning('[Webhook] Verification failed');

        return response('Forbidden', 403);
    }

    /**
     * --------------------------------------------------------------------------
     * Receive Webhook
     * --------------------------------------------------------------------------
     */
    private function receiveWebhook(Request $request)
    {
        $payload = $request->all();

        $message = data_get(
            $payload,
            'entry.0.changes.0.value.messages.0'
        );

        /**
         * Abaikan event delivered/read/status.
         */
        if (! $message) {
            return response()->json([
                'status' => 'ignored',
            ]);
        }

        $messageId = data_get($message, 'id');

        if (! $messageId) {
            return response()->json([
                'status' => 'ignored',
            ]);
        }

        /**
         * ----------------------------------------------------------------------
         * Anti Duplicate
         * ----------------------------------------------------------------------
         */
        if (! Cache::add(
            'wa:' . $messageId,
            true,
            now()->addDay()
        )) {

            Log::warning('[Webhook] Duplicate', [
                'message_id' => $messageId,
                'from'       => data_get($message, 'from'),
            ]);

            return response()->json([
                'status' => 'duplicate',
            ]);
        }

        $from = data_get($message, 'from');
        $type = data_get($message, 'type');

        /**
         * ----------------------------------------------------------------------
         * Log Incoming Request
         * ----------------------------------------------------------------------
         */
        Log::info('[Webhook] Incoming', [
            'message_id' => $messageId,
            'from'       => $from,
            'type'       => $type,
        ]);

        /**
         * ----------------------------------------------------------------------
         * Pesan Text
         * ----------------------------------------------------------------------
         */
        if ($type === 'text') {

            dispatch(function () use ($from) {

            $this->whatsapp->sendTextMessage(
            $from,
            WhatsAppMessages::welcome()
            );

            })->afterResponse();

            return response()->json([
                'status' => 'ok',
            ]);
        }
        
        
       

        /**
         * ----------------------------------------------------------------------
         * Pesan Gambar
         * ----------------------------------------------------------------------
         */
        if ($type === 'image') {

            $mediaId = data_get($message, 'image.id');

            if (! $mediaId) {
                return response()->json([
                    'status' => 'ignored',
                ]);
            }

            Log::info('[Webhook] Image Received', [
                'from'       => $from,
                'media_id'   => $mediaId,
                'message_id' => $messageId,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Respons Cepat
            |--------------------------------------------------------------------------
            |
            | Jangan melakukan proses berat di sini.
            | Cukup beri tahu bahwa foto sudah diterima.
            |
            */

            dispatch(function () use ($from) {

                $this->whatsapp->sendTextMessage(
                    $from,
                    WhatsAppMessages::receiptReceived()
                );

            })->afterResponse();

            /*
            |--------------------------------------------------------------------------
            | Dispatch Job ke Redis Queue
            |--------------------------------------------------------------------------
            */

            Log::info('[Webhook] Dispatching Job to Redis Queue');

            ProcessReceiptJob::dispatch(
                $from,
                $mediaId
            );

            /*
            |--------------------------------------------------------------------------
            | Balas Meta Secepat Mungkin
            |--------------------------------------------------------------------------
            */

            return response()->json([
                'status' => 'ok',
            ]);
        }

        return response()->json([
            'status' => 'ignored',
        ]);
    }
}