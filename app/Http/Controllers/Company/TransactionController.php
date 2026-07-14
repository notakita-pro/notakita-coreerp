<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Services\PurchaseListService;
use App\Services\DashboardSummaryService;
use App\Services\MembershipService;

class TransactionController extends Controller
{
    public function __construct(
        protected PurchaseListService $purchaseService
    ) {
    }

    /**
     * ==========================================================
     * ADMIN
     * ==========================================================
     */
    public function index(
        Company $company
    ) {
        $purchases = $this->purchaseService->paginate(
            $company
        );

        return view(
            'transaction.index',
            [
                'company'   => $company,
                'purchases' => $purchases,
                'activeTab' => 'purchase',
            ]
        );
    }

    /**
     * ==========================================================
     * CUSTOMER
     * ==========================================================
     */
    public function indexByToken(
        Request $request
    ) {
        /** @var Company $company */
        $company = $request->attributes->get(
            'company'
        );

        $purchases = $this->purchaseService->paginate(
            $company
        );

        return view(
            'transaction.index',
            [
                'company'   => $company,
                'purchases' => $purchases,
                'activeTab' => 'purchase',
            ]
        );
    }
}