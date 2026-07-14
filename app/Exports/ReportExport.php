<?php

namespace App\Exports;

use App\Models\Company;
use App\Services\ReportService;
use App\Exports\ExcelHelper;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;


class ReportExport implements
    FromCollection,
    WithHeadings,
    WithEvents,
    WithCustomStartCell,
    ShouldAutoSize
{
    /**
     * Nomor urut.
     */
    private int $no = 1;

    /**
     * Data laporan.
     */
    private Collection $rows;

    public function __construct(

        protected Company $company,

        protected $from,

        protected $to

    ) {

        $this->rows = app(ReportService::class)->period(

            $company,

            $from,

            $to

        );

    }

    /**
     * Posisi awal tabel.
     */
    public function startCell(): string
    {
        return 'A5';
    }

    /**
     * Heading.
     */
    public function headings(): array
    {
        return [

            'No',

            'Tanggal',

            'No Nota',

            'Supplier',

            'Nama Barang',

            'Qty',

            'Satuan',

            'Harga',

            'Subtotal',

        ];
    }

    /**
     * Isi tabel.
     */
    public function collection(): Collection
    {
        return $this->rows->map(function ($detail) {

            return [

                $this->no++,

                optional(
                    $detail->purchase?->invoice_date
                )->format('d-m-Y'),

                $detail->purchase?->invoice_number,

                $detail->purchase?->supplier?->name,

                $detail->item?->name,

                $detail->qty,

                $detail->item?->unit,

                $detail->unit_price,

                $detail->total_price,

            ];

        });

    }
        /**
     * Styling setelah sheet selesai dibuat.
     */
    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet;

                /*
                |--------------------------------------------------------------------------
                | Header Laporan
                |--------------------------------------------------------------------------
                */

                $sheet->setCellValue(
                    'A1',
                    'LAPORAN PEMBELIAN'
                );

                $sheet->setCellValue(
                    'A2',
                    $this->company->name
                );

                $sheet->setCellValue(
                    'A3',
                    'Periode : '
                    . date('d-m-Y', strtotime($this->from))
                    . ' s/d '
                    . date('d-m-Y', strtotime($this->to))
                );

                $sheet->mergeCells('A1:I1');
                $sheet->mergeCells('A2:I2');
                $sheet->mergeCells('A3:I3');

                /*
                |--------------------------------------------------------------------------
                | Style Dasar
                |--------------------------------------------------------------------------
                */

                ExcelHelper::title($sheet);

                ExcelHelper::tableHeader($sheet);

                $lastRow = $sheet->getHighestRow();

                ExcelHelper::tableBody(
                    $sheet,
                    $lastRow
                );

                ExcelHelper::columnWidth($sheet);

                ExcelHelper::freeze($sheet);

                /*
                |--------------------------------------------------------------------------
                | Ringkasan
                |--------------------------------------------------------------------------
                */

                $summary = app(ReportService::class)
                    ->summary($this->rows);

                $summaryRow = $lastRow + 3;

                ExcelHelper::summary(

                    $sheet,

                    $summaryRow,

                    $summary

                );

                /*
                |--------------------------------------------------------------------------
                | Footer
                |--------------------------------------------------------------------------
                */

                ExcelHelper::footer(

                    $sheet,

                    $summaryRow + 1

                );

            },

        ];
    }
}