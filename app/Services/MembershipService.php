<?php

namespace App\Services;

use App\Models\Company;
use Illuminate\Http\Request;

class MembershipService
{
    public function __construct(
        protected BillingService $billing
    ) {
    }

    /**
     * ==========================================================================
     * Membership Information
     * ==========================================================================
     */
    public function get(
        Company $company
    ): array {

        $type = strtolower(
            $company->membership_type ?? 'free'
        );

        $packages = config('membership');

        if (! isset($packages[$type])) {
            $type = 'free';
        }

        $package = $packages[$type];

        $quota = (int) $package['quota'];

        $used = (int) ($company->used_quota ?? 0);

        if ($quota === -1) {

            $remaining = 'Unlimited';
            $progress = 100;

        } else {

            $remaining = max(
                $quota - $used,
                0
            );

            $progress = $quota > 0
                ? round(($remaining / $quota) * 100)
                : 0;
        }

        return [

            'type' => $type,

            'name' => $package['name'],

            'icon' => $package['icon'],

            'color' => $package['color'],

            'quota' => $quota,

            'used' => $used,

            'remaining' => $remaining,

            'progress' => $progress,

            'price' => $package['price'],

            'duration' => $package['duration'],

            /*
            |--------------------------------------------------------------------------
            | Feature
            |--------------------------------------------------------------------------
            */

            'export_excel' => $package['export_excel'] ?? false,

            'export_pdf' => $package['export_pdf'] ?? false,

            'business_ai' => $package['business_ai'] ?? false,

            /*
            |--------------------------------------------------------------------------
            | Backward Compatibility
            |--------------------------------------------------------------------------
            */

            'excel' => $package['export_excel'] ?? false,

            'pdf' => $package['export_pdf'] ?? false,

            'bpo' => $package['business_ai'] ?? false,

            'expires_at' => $company->membership_expires_at,

            /*
            |--------------------------------------------------------------------------
            | Invoice Pending
            |--------------------------------------------------------------------------
            */

            'pending_order' => $company
                ->membershipOrders()
                ->pending()
                ->where('expires_at', '>', now())
                ->latest()
                ->first(),

        ];
    }

    /**
     * ==========================================================================
     * Upgrade Membership
     * ==========================================================================
     */
    public function upgrade(
        Company $company,
        Request $request
    ) {

        $package = strtolower(
            $request->input('package')
        );

        $config = config("membership.$package");

        abort_unless(
            $config,
            404
        );

        if ($config['price'] <= 0) {

            return back()->with(
                'error',
                'Paket tersebut tidak dapat dibeli.'
            );

        }

        $order = $this->billing->createMembershipOrder(

            $company,

            $package,

            $config['price']

        );

        return redirect()->route(

            'company.payment',

            [

                'token' => $company->access_token,

                'order' => $order,

            ]

        );

    }
}