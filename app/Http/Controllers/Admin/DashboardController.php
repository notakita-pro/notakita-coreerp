<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminDashboardService;

class DashboardController extends Controller
{
    /**
     * ==========================================================
     * ADMIN DASHBOARD
     * ==========================================================
     *
     * Dashboard utama Platform CoreERP.
     *
     * Menampilkan ringkasan:
     * - Company
     * - Membership
     * - Billing
     * - AI Monitoring
     * - System Monitoring
     *
     * Seluruh data dashboard disediakan oleh
     * AdminDashboardService agar controller
     * tetap tipis dan mudah dirawat.
     */

    /**
     * --------------------------------------------------------------------------
     * Constructor
     * --------------------------------------------------------------------------
     */
    public function __construct(
        protected AdminDashboardService $dashboardService
    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Dashboard Administrator
     * --------------------------------------------------------------------------
     */
    public function index()
    {
        return view(
            'admin.dashboard',
            [
                'dashboard' => $this->dashboardService->summary(),
            ]
        );
    }
}