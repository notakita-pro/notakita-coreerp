<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\SalesHeader;
use App\Services\CustomerService;
use App\Services\SalesService;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function __construct(
        protected SalesService $salesService,
        protected CustomerService $customerService
    ) {}

    /**
     * Dashboard Penjualan
     */
    public function index(Request $request, ?Company $company = null) 
    {
        $company = $this->resolveCompany($request, $company);

        // Perbaikan: Inisialisasi basis query secara benar
        $query = SalesHeader::query()
            ->with('customer')
            ->where('company_id', $company->id);

        // Perbaikan: Mengambil data customers milik company secara utuh ke dalam variabel
        $customers = $this->customerService->list($company);

        return view('transaction.sales.index', [
            'company'          => $company,
            'activeTab'        => 'sales',
            'sales'            => (clone $query)->latest('invoice_date')->latest('id')->paginate(15),
            'totalInvoice'     => (clone $query)->count(),
            'totalSales'       => (clone $query)->sum('grand_total'),
            'totalReceivable'  => (clone $query)->sum('balance_due'),
            'totalCustomer'    => $this->customerService->count($company),
            'customers'        => $customers,
        ]);
    }

/**
     * Form Tambah Penjualan
     */
    public function create(Request $request, ?Company $company = null) 
    {
        $company = $this->resolveCompany($request, $company);

        // Tambahkan Logika untuk mencari selectedCustomer dari database
        $selectedCustomer = null;
        if ($request->has('customer_id')) {
            // Pastikan model Customer Anda di-import di atas (use App\Models\Customer;)
            // Dan pastikan kustomer tersebut memang milik company yang sedang login
            $selectedCustomer = \App\Models\Customer::where('company_id', $company->id)
                ->find($request->query('customer_id'));
        }

        return view('transaction.sales.create', [
            'company'          => $company,
            'customers'        => $this->customerService->list($company),
            'selectedCustomer' => $selectedCustomer, // Kirim variabel ini ke View Blade
        ]);
    }

    /**
     * Simpan Penjualan
     */
    public function store(Request $request, ?Company $company = null) 
    {
        $company = $this->resolveCompany($request, $company);
        
        $request->validate([
            'invoice_date'         => ['required', 'date'],
            'customer_id'          => ['nullable'],
            'payment_term'         => ['nullable', 'string'],
            'payment_method'       => ['nullable', 'string'],
            'items'                => ['required', 'array', 'min:1'],
            'new_customer_name'    => ['nullable', 'string', 'max:150'],
            'new_customer_address' => ['nullable', 'string'],
 
        ]);

        $data = $request->all();
        $data['customer_id'] = $this->customerService->resolveCustomer($company, $data);

        $sale = $this->salesService->store($company, $data);

        if ($request->route('token')) {

    return redirect()
        ->route('company.sales.edit', [
            'token' => $request->route('token'),
            'sale'  => $sale,
        ])
        ->with('success', 'Penjualan berhasil disimpan.');

}

return redirect()
    ->route('admin.company.sales.edit', [
        'company' => $company,
        'sale'    => $sale,
    ])
    ->with('success', 'Penjualan berhasil disimpan.');
    }

    /**
     * Form Edit Penjualan
     */
    public function edit(Request $request, ?Company $company = null, $token = null, $sale = null) 
    {
        $company = $this->resolveCompany($request, $company);
        
        // Kondisi Cerdas: Jika parameter URL admin (tanpa token), 
        // maka argumen ke-3 ($token) sebenarnya adalah ID Sales-nya.
        if (is_numeric($token) && $sale === null) {
            $saleId = (int) $token;
        } else {
            // Jika lewat route token, ID Sales berada di argumen ke-4 ($sale)
            $saleId = $sale instanceof SalesHeader ? $sale->id : (int) $sale;
        }

        $saleData = $this->findSale($company, $saleId);

        return view('transaction.sales.edit', [
            'company'   => $company,
            'sale'      => $saleData,
            'customers' => $this->customerService->list($company),
            'token'     => $sale === null ? null : $token, // Teruskan token ke view jika ada
        ]);
    }

    /**
     * Update Penjualan
     */
    public function update(Request $request, ?Company $company = null, $token = null, $sale = null) 
    {
        $company = $this->resolveCompany($request, $company);
        
        // Kondisi Cerdas penentuan ID Sales
        if (is_numeric($token) && $sale === null) {
            $saleId = (int) $token;
        } else {
            $saleId = $sale instanceof SalesHeader ? $sale->id : (int) $sale;
        }

        $saleModel = $this->findSale($company, $saleId);

        $request->validate([
            'invoice_date'         => ['required', 'date'],
            'customer_id'          => ['nullable'],
            'payment_term'         => ['nullable', 'string'],
            'payment_method'       => ['nullable', 'string'],
            'new_customer_name'    => ['nullable', 'string', 'max:150'],
            'new_customer_address' => ['nullable', 'string'],
        ]);

        $data = $request->all();
        $data['customer_id'] = $this->customerService->resolveCustomer($company, $data);

        $this->salesService->update($saleModel, $data);

        if ($request->route('token') || ($sale !== null && !is_numeric($token))) {
            return redirect()
                ->route('company.sales.edit', [
                    'token' => $request->route('token') ?? $token,
                    'sale'  => $saleModel->id,
                ])
                ->with('success', 'Data penjualan berhasil diperbarui.');
        }

        return redirect()
            ->route('admin.company.sales.edit', [
                'company' => $company->id,
                'sale'    => $saleModel->id,
            ])
            ->with('success', 'Data penjualan berhasil diperbarui.');
    }

    /**
     * Cari Sales Milik Company
     */
    private function findSale(Company $company, int $id): SalesHeader 
    {
        return SalesHeader::query()
            ->where('company_id', $company->id)
            ->with(['customer', 'details'])
            ->findOrFail($id);
    }

    /**
     * Resolve Company
     */
    private function resolveCompany(Request $request, ?Company $company = null): Company 
    {
        if ($company instanceof Company) {
            return $company;
        }

        $company = $request->attributes->get('company');
        if ($company instanceof Company) {
            return $company;
        }

        $routeCompany = $request->route('company');
        if ($routeCompany instanceof Company) {
            return $routeCompany;
        }

        if (is_numeric($routeCompany)) {
            $company = Company::find($routeCompany);
            if ($company) {
                return $company;
            }
        }

        abort(404, 'Company tidak ditemukan.');
    }
}