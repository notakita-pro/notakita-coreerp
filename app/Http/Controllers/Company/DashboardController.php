<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\PurchaseHeader;
use App\Services\DashboardSummaryService;
use App\Services\MembershipService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardSummaryService $dashboardService,
        protected MembershipService $membershipService,
    ) {
    }

    /**
     * ==========================================================
     * COMPANY DASHBOARD
     * ==========================================================
     */
    public function index(
        Request $request,
        string $token
    ) {
        /** @var Company|null $company */
        $company = $request->attributes->get('company');

        abort_unless($company, 404);

        $dashboard = $this->dashboardService->snapshot($company);

        $membership = $this->membershipService->get($company);

        $purchases = PurchaseHeader::query()
            ->with([
                'supplier',
                'details',
                'company',
            ])
            ->where('company_id', $company->id)
            ->latest('invoice_date')
            ->latest('id')
            ->take(2)
            ->get();

        return view('dashboard.home', [
            'company'    => $company,
            'dashboard'  => $dashboard,
            'membership' => $membership,
            'purchases'  => $purchases,
            'token'      => $token,
        ]);
    }
}