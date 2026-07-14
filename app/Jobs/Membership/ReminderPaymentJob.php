<?php

namespace App\Jobs\Membership;

use App\Models\MembershipOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ReminderPaymentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $orders = MembershipOrder::where('status', 'PENDING')
            ->whereBetween(
                'expires_at',
                [
                    now(),
                    now()->addDay()
                ]
            )
            ->get();

        foreach ($orders as $order) {

            Log::info(
                'Payment reminder',
                [
                    'invoice' => $order->invoice_number,
                    'company' => optional($order->company)->name,
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | Nanti diganti menjadi:
            |--------------------------------------------------------------------------
            |
            | WhatsAppService::send(...)
            |
            */
        }
    }
}