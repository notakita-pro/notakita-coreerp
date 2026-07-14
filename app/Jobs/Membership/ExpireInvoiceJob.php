<?php

namespace App\Jobs\Membership;

use App\Models\MembershipOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ExpireInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $orders = MembershipOrder::where('status', 'PENDING')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->get();

        foreach ($orders as $order) {

            $order->markExpired();

            if ($payment = $order->latestPayment()) {

                $payment->markExpired();

            }

            Log::info(
                'Invoice expired',
                [
                    'invoice' => $order->invoice_number,
                ]
            );
        }
    }
}