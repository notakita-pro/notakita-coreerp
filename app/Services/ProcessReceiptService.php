<?php

namespace App\Services;

use App\Models\Company;
use App\Support\WhatsAppMessages;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessReceiptService
{
    public function __construct(
        protected MediaService $media,
        protected VisionService $vision,
        protected ReceiptSaverService $saver,
        protected WhatsAppService $whatsapp,
        protected QuotaService $quotaService,
        protected MembershipService $membershipService,
    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Proses Nota
     * --------------------------------------------------------------------------
     */
    public function handle(
        string $from,
        string $mediaId
    ): void {

        $cacheKey = 'processing:' . $from;

        try {

            Log::info('[ProcessReceipt] START', [
                'from'     => $from,
                'media_id' => $mediaId,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Anti Spam
            |--------------------------------------------------------------------------
            */

            if (! Cache::add(
                $cacheKey,
                [
                    'started_at' => now()->toDateTimeString(),
                    'media_id'   => $mediaId,
                ],
                now()->addMinutes(2)
            )) {

                $this->whatsapp->sendTextMessage(
                    $from,
                    WhatsAppMessages::previousReceiptProcessing()
                );

                return;
            }

            /*
            |--------------------------------------------------------------------------
            | Download Media
            |--------------------------------------------------------------------------
            */

            $image = $this->media->download($mediaId);

            Log::info('[ProcessReceipt] Media downloaded', $image);

            /*
            |--------------------------------------------------------------------------
            | Company
            |--------------------------------------------------------------------------
            */

            $company = Company::firstOrCreate(
                [
                    'phone' => $from,
                ],
                [
                    'name' => '--',
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | Validasi Kuota
            |--------------------------------------------------------------------------
            */

            if (! $this->quotaService->hasQuota($company)) {

                $membership = $this->membershipService->get($company);

                $nextPackage = match ($membership['type']) {

                    'free'   => 'Silver',

                    'silver' => 'Gold',

                    default  => null,

                };

                $this->whatsapp->sendCtaUrl(

                    $from,

                    WhatsAppMessages::quotaExceeded(
                        $membership,
                        $nextPackage
                    ),

                    WhatsAppMessages::membershipButton(),

                    $company->membershipUrl(),

                    WhatsAppMessages::membershipHeader(),

                    WhatsAppMessages::membershipFooter()

                );

                return;
            }

            /*
            |--------------------------------------------------------------------------
            | OCR Gemini
            |--------------------------------------------------------------------------
            */

            $receipt = $this->vision->analyze(
                $image['path'],
                $from
            );

            Log::info('[ProcessReceipt] Receipt analyzed', [

                'supplier'       => $receipt->supplier,

                'invoice_number' => $receipt->invoice_number,

                'total'          => $receipt->total,

                'items'          => count($receipt->items),

            ]);

            /*
            |--------------------------------------------------------------------------
            | Simpan Database
            |--------------------------------------------------------------------------
            */
$purchase = $this->saver->save(
    $company,
    $receipt,
    $image['path']
);

$purchaseId = $purchase->id;


/*
|--------------------------------------------------------------------------
| Pastikan Company Memiliki Access Token
|--------------------------------------------------------------------------
*/

if (empty($company->access_token)) {

    DashboardSecurityService::resetToken($company);

    $company->refresh();

}

/*
|--------------------------------------------------------------------------
| Ringkasan OCR
|--------------------------------------------------------------------------
*/

$supplier = $receipt->supplier ?? '-';

$date = $receipt->date ?? '-';

$total = $receipt->total !== null
    ? 'Rp ' . number_format(
        $receipt->total,
        0,
        ',',
        '.'
    )
    : '-';

$itemCount = count($receipt->items);

$this->whatsapp->sendTextMessage(

    $from,

    WhatsAppMessages::receiptSuccess(

        $supplier,

        $date,

        $total,

        $itemCount

    )

);

/*
|--------------------------------------------------------------------------
| CTA Dashboard (Temporary Testing)
|--------------------------------------------------------------------------
*/

$transactionUrl = url(
    "/c/{$company->access_token}/purchase/{$purchaseId}"
);

Log::info('[ProcessReceipt] CTA Dashboard', [

    'company_id' => $company->id,

    'token'      => $company->access_token,

    'url'        => $transactionUrl,

]);

$this->whatsapp->sendCtaUrl(
    $from,
    "Klik tombol di bawah ini.",
    "Lihat Data Lengkap",
    $transactionUrl
);

Log::info('[ProcessReceipt] DONE');
           } catch (Throwable $e) {

            Log::error('[ProcessReceipt] FAILED', [

                'message' => $e->getMessage(),

                'file' => $e->getFile(),

                'line' => $e->getLine(),

            ]);

            $this->whatsapp->sendTextMessage(

                $from,

                WhatsAppMessages::receiptFailed(
                    $e->getMessage()
                )

            );

        } finally {

            Cache::forget($cacheKey);

        }
    
    }
}