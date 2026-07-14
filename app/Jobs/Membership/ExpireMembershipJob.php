<?php

namespace App\Jobs\Membership;

use App\Models\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ExpireMembershipJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $companies = Company::whereNotNull('membership_expires_at')
            ->where('membership_expires_at', '<', now())
            ->where('membership_type', '!=', 'free')
            ->get();

        foreach ($companies as $company) {

            $company->update([

                'membership_type'       => 'free',

                'membership_expires_at' => null,

                'used_quota'            => 0,

            ]);

            Log::info(
                'Membership expired',
                [
                    'company_id' => $company->id,
                    'company'    => $company->name,
                ]
            );
        }
    }
}