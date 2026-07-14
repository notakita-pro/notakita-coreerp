<?php

namespace App\Services;

use App\Models\PurchaseHeader;

class PurchaseValidationService
{
    public function analyze(PurchaseHeader $purchase): PurchaseHeader
    {
        $warnings = [];

        $calculatedSubtotal = 0;

        foreach ($purchase->details as $detail) {

            $qty      = (float) $detail->qty;
            $price    = (float) $detail->unit_price;
            $subtotal = (float) $detail->total_price;

            $expectedSubtotal = $qty * $price;

            $difference = abs($expectedSubtotal - $subtotal);

            $detail->expected_subtotal = $expectedSubtotal;
            $detail->difference = $difference;
            $detail->is_valid = $difference <= 1;

            if (!$detail->is_valid) {

                $warnings[] = [
                    'type' => 'detail',
                    'message' => "Subtotal item {$detail->item?->name} tidak sesuai."
                ];

            }

            $calculatedSubtotal += $subtotal;
        }

        $expectedTotal = $calculatedSubtotal + ($purchase->tax ?? 0);

        $headerDifference = abs($purchase->total - $expectedTotal);

        if ($headerDifference > 1) {

            $warnings[] = [
                'type' => 'header',
                'message' => 'Grand Total tidak sama dengan jumlah seluruh subtotal.'
            ];

        }

        $purchase->calculated_subtotal = $calculatedSubtotal;
        $purchase->calculated_total = $expectedTotal;
        $purchase->difference = $headerDifference;

        $purchase->warning_count = count($warnings);
        $purchase->has_warning = count($warnings) > 0;

        /*
        |-------------------------------------------------------
        | Agar kompatibel dengan seluruh Blade
        |-------------------------------------------------------
        */

        $purchase->validation = [

            'warnings' => $warnings,

        ];

        return $purchase;
    }
}