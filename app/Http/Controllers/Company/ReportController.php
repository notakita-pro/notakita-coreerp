<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Services\DashboardSummaryService;
use App\Services\ReportService;

class ReportController extends Controller
{
    public function __construct(
        protected ReportService $reportService,
        protected DashboardSummaryService $dashboardService,
    ) {
    }

    /**
     * ==========================================================
     * Dashboard Report
     * ==========================================================
     */
    public function dashboard(Request $request, ?Company $company = null)
{
    $company = $this->resolveCompany($request, $company);

    return view('report.dashboard', [
        'company'   => $company,
        'dashboard' => $this->dashboardService->snapshot($company),
    ]);
}

    /**
     * ==========================================================
     * Form Generate Report
     * ==========================================================
     */
   public function generateForm(Request $request, ?Company $company = null)
{
    $company = $this->resolveCompany($request, $company);

    return view('report.generate', [
        'company'   => $company,
        'dashboard' => $this->dashboardService->snapshot($company),
        'rows'      => collect(),
        'summary'   => null,
        'from'      => '',
        'to'        => '',
    ]);
}

    /**
     * ==========================================================
     * Generate Report
     * ==========================================================
     */
    public function generate(Request $request, ?Company $company = null)
    {
        $company = $this->resolveCompany($request, $company);

        $validated = $request->validate([
            'from' => ['required', 'date'],
            'to'   => ['required', 'date', 'after_or_equal:from'],
        ]);

        $rows = $this->reportService->period(
            $company,
            $validated['from'],
            $validated['to']
        );

       return view('report.generate', [
    'company'   => $company,
    'dashboard' => $this->dashboardService->snapshot($company),
    'rows'      => $rows,
    'summary'   => $this->reportService->summary($rows),
    'from'      => $validated['from'],
    'to'        => $validated['to'],
]);
    }

    /**
     * ==========================================================
     * Report Hari Ini
     * ==========================================================
     */
    public function today(Request $request, ?Company $company = null)
    {
        $company = $this->resolveCompany($request, $company);

        $rows = $this->reportService->today($company);

        return view('report.generate', [
    'company'   => $company,
    'dashboard' => $this->dashboardService->snapshot($company),
    'rows'      => $rows,
    'summary'   => $this->reportService->summary($rows),
    'from'      => now()->toDateString(),
    'to'        => now()->toDateString(),
]);
    }

    /**
     * ==========================================================
     * Resolve Company
     * ==========================================================
     *
     * Admin:
     *   /company/{company}
     *
     * Customer:
     *   /c/{token}
     */
    private function resolveCompany(Request $request, ?Company $company = null): Company
    {
        // Route Model Binding (Admin)
        if ($company instanceof Company) {
            return $company;
        }

        // Middleware company.auth (Customer)
        $company = $request->attributes->get('company');

        if ($company instanceof Company) {
            return $company;
        }

        // Fallback apabila route binding tidak aktif
        $routeCompany = $request->route('company');

        if ($routeCompany instanceof Company) {
            return $routeCompany;
        }

        if (is_numeric($routeCompany)) {
            $company = Company::find($routeCompany);

            if ($company) {
                return $company;
            }
        }

        abort(404, 'Company tidak ditemukan.');
    }
}