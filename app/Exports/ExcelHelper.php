<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ExcelHelper
{
    /**
     * Style Header Laporan
     */
    public static function title($sheet): void
    {
        $sheet->getStyle('A1:I1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        $sheet->getStyle('A2:I2')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 13,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        $sheet->getStyle('A3:I3')->applyFromArray([
            'font' => [
                'italic' => true,
                'size' => 11,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);
    }

    /**
     * Header tabel
     */
    public static function tableHeader($sheet): void
    {
        $sheet->getStyle('A5:I5')->applyFromArray([

            'font' => [
                'bold' => true,
                'size' => 10,
                'color' => [
                    'rgb' => 'FFFFFF',
                ],
            ],

            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '2563EB',
                ],
            ],

            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],

        ]);
    }

    /**
     * Style isi tabel
     */
    public static function tableBody($sheet, int $lastRow): void
    {
        $sheet->getStyle("A6:I{$lastRow}")
            ->getFont()
            ->setSize(10);

        $sheet->getStyle("A5:I{$lastRow}")
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        // No
        $sheet->getStyle("A6:A{$lastRow}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Tanggal
        $sheet->getStyle("B6:B{$lastRow}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Qty
        $sheet->getStyle("F6:F{$lastRow}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Satuan
        $sheet->getStyle("G6:G{$lastRow}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Harga & Subtotal
        $sheet->getStyle("H6:I{$lastRow}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $sheet->getStyle("F6:F{$lastRow}")
            ->getNumberFormat()
            ->setFormatCode('#,##0');

        $sheet->getStyle("H6:I{$lastRow}")
            ->getNumberFormat()
            ->setFormatCode('#,##0');
    }

    /**
     * Lebar kolom
     */
    public static function columnWidth($sheet): void
    {
        $sheet->getColumnDimension('A')->setWidth(6);   // No
        $sheet->getColumnDimension('B')->setWidth(10);  // Tanggal
        $sheet->getColumnDimension('C')->setWidth(8);  // No Nota

        $sheet->getColumnDimension('D')->setAutoSize(true); // Supplier
        $sheet->getColumnDimension('E')->setAutoSize(true); // Nama Barang

        $sheet->getColumnDimension('F')->setWidth(6);   // Qty
        $sheet->getColumnDimension('G')->setWidth(7);  // Satuan
        $sheet->getColumnDimension('H')->setWidth(13);  // Harga
        $sheet->getColumnDimension('I')->setWidth(14);  // Subtotal
    }

    /**
     * Freeze Pane
     */
    public static function freeze($sheet): void
    {
        $sheet->freezePane('A6');
        $sheet->getRowDimension(5)->setRowHeight(24);
    }
        /**
     * Ringkasan laporan
     */
    public static function summary(
        $sheet,
        int $row,
        array $summary
    ): void {

        /*
        |--------------------------------------------------------------------------
        | Area Ringkasan (kanan bawah)
        |--------------------------------------------------------------------------
        */

        $sheet->mergeCells("G{$row}:H{$row}");
        $sheet->mergeCells("G".($row + 1).":H".($row + 1));
        $sheet->mergeCells("G".($row + 2).":H".($row + 2));

        $sheet->setCellValue(
            "G{$row}",
            'Jumlah Nota'
        );

        $sheet->setCellValue(
            "I{$row}",
            $summary['transactions']
        );

        $sheet->setCellValue(
            "G".($row + 1),
            'Jumlah Supplier'
        );

        $sheet->setCellValue(
            "I".($row + 1),
            $summary['suppliers']
        );

        $sheet->setCellValue(
            "G".($row + 2),
            'Total Belanja'
        );

        $sheet->setCellValue(
            "I".($row + 2),
            $summary['total']
        );

        // Font
        $sheet->getStyle("G{$row}:I".($row + 2))
            ->getFont()
            ->setSize(10);

        // Total Belanja Bold
        $sheet->getStyle("G".($row + 2).":I".($row + 2))
            ->getFont()
            ->setBold(true);

        // Border
        $sheet->getStyle("G{$row}:I".($row + 2))
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        // Alignment label
            $sheet->getStyle("G{$row}:H".($row + 2))
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_LEFT)
                ->setVertical(Alignment::VERTICAL_CENTER);
            
            // Alignment nilai
            $sheet->getStyle("I{$row}:I".($row + 2))
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_RIGHT)
                ->setVertical(Alignment::VERTICAL_CENTER);  

        // Format Rupiah
        $sheet->getStyle("I".($row + 2))
            ->getNumberFormat()
            ->setFormatCode('#,##0');
    }

    /**
     * Footer
     */
    public static function footer($sheet, int $row): void
{
    // Footer dimulai pada baris kedua area ringkasan
    $sheet->mergeCells("A{$row}:C{$row}");
    $sheet->mergeCells("A".($row+1).":C".($row+1));

    $sheet->setCellValue(
        "A{$row}",
        'Dicetak : '.now()->format('d-m-Y H:i').' WIB'
    );

    $sheet->setCellValue(
        "A".($row+1),
        'Elbeje-CoreERP v1.0'
    );

    $sheet->getStyle("A{$row}:C".($row+1))
        ->applyFromArray([
            'font' => [
                'italic' => true,
                'size'   => 9,
                'color'  => [
                    'rgb' => '808080',
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
        ]);
}
}