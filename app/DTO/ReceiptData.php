<?php

namespace App\DTO;

class ReceiptData
{
    public ?string $supplier = null;

    public ?string $invoice_number = null;

    public ?string $date = null;

    public ?float $subtotal = null;

    public ?float $tax = null;

    public ?float $discount = null;

    public ?float $total = null;

    /**
     * @var array<int, array>
     */
    public array $items = [];

    /**
     * Membuat DTO dari array hasil Gemini.
     */
    public static function fromArray(array $data): self
    {
        $dto = new self();

        $dto->supplier = $data['supplier'] ?? null;
        $dto->invoice_number = $data['invoice_number'] ?? null;
        $dto->date = $data['date'] ?? null;

        $dto->subtotal = self::number($data['subtotal'] ?? null);
        $dto->tax = self::number($data['tax'] ?? null);
        $dto->discount = self::number($data['discount'] ?? null);

        /*
        |--------------------------------------------------------------------------
        | Gemini kadang memakai grand_total
        |--------------------------------------------------------------------------
        */
        if (array_key_exists('grand_total', $data)) {
            $dto->total = self::number($data['grand_total']);
        } else {
            $dto->total = self::number($data['total'] ?? null);
        } // <-- DI SINI TADI KURUNG KURAWALNYA HILANG, SOB!

        /*
        |--------------------------------------------------------------------------
        | Bersihkan Item
        |--------------------------------------------------------------------------
        */
        $dto->items = [];

        if (is_array($data['items'] ?? null)) {
            foreach ($data['items'] as $item) {
                if (!is_array($item)) {
                    continue;
                }

                $dto->items[] = [
                    'name' => trim((string) ($item['name'] ?? '')),
                    'qty' => self::number($item['qty'] ?? null),
                    'unit_price' => self::number($item['unit_price'] ?? null),
                    'total' => self::number($item['total'] ?? null),
                ];
            }
        }

        return $dto;
    }

    /**
     * Konversi DTO menjadi array.
     */
    public function toArray(): array
    {
        return [
            'supplier'       => $this->supplier,
            'invoice_number' => $this->invoice_number,
            'date'           => $this->date,
            'subtotal'       => $this->subtotal,
            'tax'            => $this->tax,
            'discount'       => $this->discount,
            'total'          => $this->total,
            'items'          => $this->items,
        ];
    }

    /**
     * Normalisasi angka.
     *
     * Mendukung:
     * 313513.51
     * 313.513,51
     * 313513
     * "313513.51"
     * "313.513,51"
     * "Rp 313.513,51"
     */
    private static function number($value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_int($value) || is_float($value)) {
            return (float) $value;
        }

        $value = trim((string) $value);

        // Format Indonesia (Contoh: 150.000,00 -> 150000.00)
        if (str_contains($value, ',')) {
            $value = str_replace('.', '', $value);
            $value = str_replace(',', '.', $value);
        }

        // Sisakan hanya angka, titik, dan minus
        $value = preg_replace('/[^0-9.\-]/', '', $value);

        return is_numeric($value) ? (float) $value : null;
    }
}