<?php

namespace App\Services;

use App\Models\Company;

class DashboardSummaryService
{
    public function __construct(
        protected ReportService $reportService,
    ) {
    }

    /**
     * Snapshot Dashboard Perusahaan
     *
     * Digunakan oleh Home Dashboard.
     */
    public function snapshot(Company $company): array
    {
        $rows = $this->reportService->all($company);
        $summary = $this->reportService->summary($rows);

        return [

            /*
            |--------------------------------------------------------------------------
            | Operasional
            |--------------------------------------------------------------------------
            */
          'wallet' => [

    'purchase_total'   => $summary['total'],

    'transaction_count'=> $summary['transactions'],

    'supplier_count'   => $summary['suppliers'],

    'product_count'    => $summary['items'],

],

            /*
            |--------------------------------------------------------------------------
            | Financial KPI
            |--------------------------------------------------------------------------
            | Akan berkembang ketika modul Sales & Finance selesai.
            */

           'finance' => [
            
                'purchase'          => $summary['total'],
            
                'sales'             => 0,
            
                'gross_profit'      => 0,
            
                'net_profit'        => 0,
            
                'operational_cost'  => 0,
            
                'receivable'        => 0,
            
                'payable'           => 0,
            
            ],
            
           'activity' => [],

            'inventory' => [],

            'sales' => [],

            'ai' => [],
            

        ];
    }
}