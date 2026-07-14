<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Customer;

class CustomerService
{
    /**
     * ==========================================================
     * List Customer
     * ==========================================================
     */
    public function list(
        Company $company
    ) {

        return Customer::query()

            ->where(
                'company_id',
                $company->id
            )

            ->orderBy('name')

            ->orderByRaw('address IS NULL')

            ->orderBy('address')

            ->get();

    }

    /**
     * ==========================================================
     * Total Customer
     * ==========================================================
     */
    public function count(
        Company $company
    ): int {

        return Customer::query()

            ->where(
                'company_id',
                $company->id
            )

            ->count();

    }

    /**
     * ==========================================================
     * Resolve Customer
     * ==========================================================
     *
     * Prioritas:
     * 1. Customer Lama
     * 2. Customer Baru
     * 3. Customer Umum (NULL)
     */
    public function resolveCustomer(
        Company $company,
        array $data
    ): ?int {

        /*
        |--------------------------------------------------------------------------
        | Customer Lama
        |--------------------------------------------------------------------------
        */

        if (!empty($data['customer_id'])) {

            $customer = Customer::query()

                ->where(
                    'company_id',
                    $company->id
                )

                ->find(
                    $data['customer_id']
                );

            if ($customer) {

                return $customer->id;

            }

        }

        /*
        |--------------------------------------------------------------------------
        | Customer Baru
        |--------------------------------------------------------------------------
        */

        $name = trim(
            $data['new_customer_name'] ?? ''
        );

        $address = trim(
            $data['new_customer_address'] ?? ''
        );

        if ($name === '') {

            return null;

        }

        /*
        |--------------------------------------------------------------------------
        | Hindari Customer Ganda
        |--------------------------------------------------------------------------
        */

        $customer = Customer::query()

            ->where(
                'company_id',
                $company->id
            )

            ->whereRaw(
                'LOWER(name)=?',
                [strtolower($name)]
            )

            ->whereRaw(
                'LOWER(COALESCE(address,""))=?',
                [strtolower($address)]
            )

            ->first();

        if ($customer) {

            return $customer->id;

        }

        /*
        |--------------------------------------------------------------------------
        | Simpan Customer Baru
        |--------------------------------------------------------------------------
        */

        $customer = Customer::create([

            'company_id' => $company->id,

            'name' => $name,

            'address' => $address,

            'created_by' => auth()->id(),

        ]);

        return $customer->id;

    }
}