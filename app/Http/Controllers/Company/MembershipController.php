<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\MembershipOrder;
use App\Services\MembershipService;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function __construct(
        protected MembershipService $membershipService
    ) {
    }

    /**
     * ==========================================================================
     * Membership Center
     * ==========================================================================
     */
    public function index(
        Request $request,
        string $token
    ) {
        $company = $request->attributes->get('company');

        abort_unless($company, 404);

        return view(
            'membership.index',
            [

                'company' => $company,

                'membership' => $this->membershipService->get($company),

                'token' => $token,

            ]
        );
    }

    /**
     * ==========================================================================
     * Upgrade Membership
     * ==========================================================================
     */
    public function upgrade(
        Request $request,
        string $token
    ) {
        $company = $request->attributes->get('company');

        abort_unless($company, 404);

        return $this->membershipService->upgrade(
            $company,
            $request
        );
    }

    /**
     * ==========================================================================
     * Payment Page
     * ==========================================================================
     */
    public function payment(
        Request $request,
        string $token,
        MembershipOrder $order
    ) {
        abort_unless(

            $order->company &&
            $order->company->access_token === $token,

            404

        );

        return view(
            'membership.payment',
            [

                'company' => $order->company,

                'order' => $order,

                'token' => $token,

            ]
        );
    }
}