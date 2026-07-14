<?php

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use App\Exports\ReportExport;
use App\Models\Company;
use App\Services\BillingService;
use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportExportController extends Controller
{
    public function __construct(
        private ReportService $reportService,
        private BillingService $billing
    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Export Excel (Access Token)
     * --------------------------------------------------------------------------
     */
    public function excelByToken(Request $request)
    {
        $company = $request->attributes->get('company');

        if (! $this->billing->hasFeature($company, 'export_excel')) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Fitur Export Excel hanya tersedia untuk Membership Silver atau Gold.'
                );

        }

        return Excel::download(

            new ReportExport(
                $company,
                $request->from,
                $request->to
            ),

            'Laporan-Pembelian-' . $company->id . '.xlsx'

        );
    }

    /**
     * --------------------------------------------------------------------------
     * Export PDF (Access Token)
     * --------------------------------------------------------------------------
     */
    public function pdfByToken(Request $request)
    {
        $company = $request->attributes->get('company');

        if (! $this->billing->hasFeature($company, 'export_pdf')) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Fitur Export PDF hanya tersedia untuk Membership Silver atau Gold.'
                );

        }

        $rows = $this->reportService->period(
            $company,
            $request->from,
            $request->to
        );

        $summary = $this->reportService->summary($rows);

        return Pdf::loadView(
            'report.pdf',
            [
                'company' => $company,
                'rows'    => $rows,
                'summary' => $summary,
                'from'    => $request->from,
                'to'      => $request->to,
            ]
        )->download('Laporan-Pembelian.pdf');
    }

    /**
     * --------------------------------------------------------------------------
     * Export Excel (Admin)
     * --------------------------------------------------------------------------
     */
    public function excel(
        Request $request,
        Company $company
    ) {

        if (! $this->billing->hasFeature($company, 'export_excel')) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Fitur Export Excel hanya tersedia untuk Membership Silver atau Gold.'
                );

        }

        return Excel::download(

            new ReportExport(
                $company,
                $request->from,
                $request->to
            ),

            'Laporan-Pembelian-' . $company->id . '.xlsx'

        );
    }

    /**
     * --------------------------------------------------------------------------
     * Export PDF (Admin)
     * --------------------------------------------------------------------------
     */
    public function pdf(
        Request $request,
        Company $company
    ) {

        if (! $this->billing->hasFeature($company, 'export_pdf')) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Fitur Export PDF hanya tersedia untuk Membership Silver atau Gold.'
                );

        }

        $rows = $this->reportService->period(
            $company,
            $request->from,
            $request->to
        );

        $summary = $this->reportService->summary($rows);

        return Pdf::loadView(
            'report.pdf',
            [
                'company' => $company,
                'rows'    => $rows,
                'summary' => $summary,
                'from'    => $request->from,
                'to'      => $request->to,
            ]
        )->download('Laporan-Pembelian.pdf');

    }
}