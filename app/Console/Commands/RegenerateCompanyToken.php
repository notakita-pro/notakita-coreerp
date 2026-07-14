<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Services\DashboardSecurityService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('company:regenerate-token')]
#[Description('Regenerate semua access token company')]
class RegenerateCompanyToken extends Command
{
    public function handle(): int
    {
        $count = 0;

        Company::chunk(100, function ($companies) use (&$count) {

            foreach ($companies as $company) {

                DashboardSecurityService::resetToken($company);

                $count++;
            }

        });

        $this->info("Berhasil regenerate {$count} company token.");

        return self::SUCCESS;
    }
}