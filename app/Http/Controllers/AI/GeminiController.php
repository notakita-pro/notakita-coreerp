<?php

namespace App\Http\Controllers\AI;

use App\Http\Controllers\Controller;
use App\Models\GeminiUsage;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Services\AIService;
use App\Services\DashboardSummaryService;

class GeminiController extends Controller
{
    public function __construct(
        protected AIService $aiService,
        protected DashboardSummaryService $dashboardSummaryService,
    ) {
    }

    /**
     * Dashboard Monitoring Gemini (Admin)
     */
    public function index()
    {
        $today = GeminiUsage::whereDate(
            'created_at',
            today()
        );

        $summary = [

            'total_request' => GeminiUsage::count(),

            'success_request' => GeminiUsage::where(
                'success',
                true
            )->count(),

            'failed_request' => GeminiUsage::where(
                'success',
                false
            )->count(),

            'today_request' => $today->count(),

            'prompt_tokens' => GeminiUsage::sum(
                'prompt_tokens'
            ),

            'output_tokens' => GeminiUsage::sum(
                'output_tokens'
            ),

            'total_tokens' => GeminiUsage::sum(
                'total_tokens'
            ),

            'avg_elapsed_ms' => round(
                GeminiUsage::avg('elapsed_ms') ?? 0
            ),

        ];

        $dailyUsage = GeminiUsage::selectRaw(
                'DATE(created_at) as tanggal,
                 SUM(total_tokens) as total_tokens,
                 COUNT(*) as total_request'
            )
            ->groupBy('tanggal')
            ->orderByDesc('tanggal')
            ->limit(30)
            ->get();

        $topCompanies = GeminiUsage::selectRaw(
                'company_phone,
                 SUM(total_tokens) as total_tokens,
                 COUNT(*) as total_request'
            )
            ->whereNotNull('company_phone')
            ->groupBy('company_phone')
            ->orderByDesc('total_tokens')
            ->limit(20)
            ->get();

        $topSuppliers = GeminiUsage::selectRaw(
                'supplier,
                 SUM(total_tokens) as total_tokens,
                 COUNT(*) as total_request'
            )
            ->whereNotNull('supplier')
            ->groupBy('supplier')
            ->orderByDesc('total_tokens')
            ->limit(20)
            ->get();

        $recentLogs = GeminiUsage::latest()
            ->paginate(30);

        return view(
            'gemini.index',
            compact(
                'summary',
                'dailyUsage',
                'topCompanies',
                'topSuppliers',
                'recentLogs'
            )
        );
    }

    /**
     * AI Business Advisor
     */
    public function businessAnalysis(Request $request)
    {
        $company = $request->attributes->get('company');

        $dashboard = $this->dashboardSummaryService
            ->snapshot($company);

        $analysis = $this->aiService->analyzeBusiness(
            $company,
            $dashboard
        );

        return view(
            'gemini.business-analysis',
            [
                'company'   => $company,
                'dashboard' => $dashboard,
                'analysis'  => $analysis,
            ]
        );
    }
}