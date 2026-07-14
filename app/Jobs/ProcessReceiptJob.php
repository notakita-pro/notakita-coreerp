<?php

namespace App\Jobs;

use Throwable;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\ProcessReceiptService;

class ProcessReceiptJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Maksimal percobaan.
     */
    public $tries = 2;
    public $timeout = 180;
    public bool $failOnTimeout = true;


    public function __construct(
        protected string $from,
        protected string $mediaId
    ) {
    }

    /**
     * Jalankan proses OCR + AI.
     */
    public function handle(ProcessReceiptService $processor): void
    {
        Log::info('[ProcessReceiptJob] START', [
            'from' => $this->from,
            'media_id' => $this->mediaId,
        ]);

        $processor->handle(
            $this->from,
            $this->mediaId
        );

        Log::info('[ProcessReceiptJob] DONE', [
            'from' => $this->from,
            'media_id' => $this->mediaId,
        ]);
    }

    /**
     * Dipanggil bila seluruh retry gagal.
     */
    public function failed(Throwable $e): void
    {
        Log::error('[ProcessReceiptJob] FAILED', [
            'from'     => $this->from,
            'media_id' => $this->mediaId,
            'message'  => $e->getMessage(),
            'file'     => $e->getFile(),
            'line'  => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);
    }
}