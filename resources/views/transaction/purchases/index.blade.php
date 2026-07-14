@extends('layouts.app')

@push('styles')
<style>

/* ==========================================================
   PURCHASE PAGE
========================================================== */

.purchase-page{
    display:flex;
    flex-direction:column;
    gap:20px;
}

/* ==========================================================
   HEADER
========================================================== */

.page-description{
    color:var(--text-muted);
    font-size:14px;
    margin-top:4px;
}

/* ==========================================================
   TAB MENU
========================================================== */

.transaction-tabs{
    display:flex;
    gap:12px;
    margin-bottom:22px;
}

.transaction-tab{
    flex:1;
    text-align:center;
    padding:12px;
    border-radius:12px;
    text-decoration:none;
    font-weight:700;
    transition:.25s;
    border:1px solid var(--border-color);
}

.transaction-tab.active{
    background:var(--primary);
    color:#fff;
}

.transaction-tab.inactive{
    background:#f8fafc;
    color:#64748b;
}

.transaction-tab.inactive:hover{
    background:#eef2ff;
}

.transaction-tab small{
    display:block;
    font-size:11px;
    font-weight:500;
    margin-top:4px;
}

/* ==========================================================
   TABLE
========================================================== */

.erp-table-wrapper{
    background:#fff;
    border-radius:16px;
    overflow:hidden;
    border:1px solid var(--border-color);
    box-shadow:var(--shadow-sm);
}

.erp-table{
    width:100%;
    border-collapse:collapse;
}

.erp-table th{
    background:#f8fafc;
    padding:15px 18px;
    font-size:12px;
    text-transform:uppercase;
    color:var(--text-muted);
    letter-spacing:.4px;
}

.erp-table td{
    padding:16px 18px;
    border-top:1px solid #edf2f7;
    vertical-align:middle;
}

.erp-table tbody tr:hover{
    background:#fbfdff;
}

.supplier-cell{
    font-size:16px;
    font-weight:600;
    background:#6bbded18;
}

.action-cell{
    display:flex;
    flex-direction:column;
    gap:6px;
}

.warning-text{
    font-size:12px;
    color:#dc2626;
    font-weight:600;
}

.empty-state{
    padding:50px 20px;
    text-align:center;
    color:#64748b;
}

/* ==========================================================
   MOBILE
========================================================== */

@media(max-width:992px){

.erp-table,
.erp-table thead,
.erp-table tbody,
.erp-table tr,
.erp-table th,
.erp-table td{
display:block;
}

.erp-table thead{
display:none;
}

.erp-table tr{
border-bottom:10px solid #f1f5f9;
padding:10px 0;
}

.erp-table td{
position:relative;
padding-left:48%;
text-align:right;
border-bottom:1px dashed #e5e7eb;
}

.erp-table td:last-child{
border-bottom:none;
}

.erp-table td:before{
content:attr(data-label);
position:absolute;
left:16px;
font-weight:700;
color:#64748b;
}

.supplier-cell{
background:none;
}

.action-cell{
align-items:flex-end;
}

.transaction-tabs{
gap:10px;
}

}

</style>
@endpush

@section('content')

<div class="purchase-page">

    <div class="page-header">
        <div>
            <h2 class="page-title">
                Transaksi
            </h2>

            <p class="page-description">
                Kelola seluruh transaksi perusahaan.
            </p>
        </div>
    </div>

    <div class="transaction-tabs">

        <a href="{{ route('company.purchase',$company->access_token) }}"
           class="transaction-tab active">

            🧾 Belanja

        </a>
        
        <a href="{{ route('company.sales',$company->access_token) }}"
           class="transaction-tab inactive">

            💰 Penjualan

        </a>
        </a>

    </div>

    <div class="erp-table-wrapper">

        <table class="erp-table">

            <thead>

                <tr>

                    <th>ID</th>

                    <th>Tanggal</th>

                    @if(empty($company))
                        <th>Client</th>
                        <th>Dashboard</th>
                    @endif

                    <th>Supplier</th>

                    <th>Total</th>

                    <th>Item</th>

                    <th>Aksi</th>

                </tr>

            </thead>

            <tbody>
                @forelse($purchases as $purchase)

<tr>

    <td data-label="ID">
        <span class="badge badge-purple">
            #{{ $purchase->id }}
        </span>
    </td>

    <td data-label="Tanggal">
        {{ optional($purchase->invoice_date)->format('d-m-Y') }}
    </td>

    @if(empty($company))

        <td data-label="Client">

            <strong>
                {{ $purchase->company?->name ?? '--' }}
            </strong>

            <br>

            <small class="text-muted">
                {{ $purchase->company?->phone ?? '-' }}
            </small>

        </td>

        <td data-label="Dashboard">

            <a href="{{ route('dashboard.company',$purchase->company_id) }}"
               class="button btn-brown">

                Lihat

            </a>

        </td>

    @endif

    <td
        data-label="Supplier"
        class="supplier-cell">

        {{ $purchase->supplier?->name ?? 'Supplier Tidak Diketahui' }}

    </td>

    <td data-label="Total">

        @php
            $warning = count($purchase->validation['warnings'] ?? []);
        @endphp

        <div class="fw-bold {{ $warning ? 'text-danger' : '' }}">

            Rp {{ number_format($purchase->total,0,',','.') }}

        </div>

        @if($warning)

            <div class="warning-text">

                ⚠️ {{ $warning }} masalah ditemukan

            </div>

        @endif

    </td>

    <td data-label="Item">

        <span class="badge badge-gray">

            {{ $purchase->details->count() }}

        </span>

    </td>

    <td data-label="Aksi">

        <div class="action-cell">

            @if($warning)

                <span class="badge badge-warning">

                    Perlu Dicek

                </span>

            @endif

            <small class="text-muted">

                {{ $purchase->created_at->diffForHumans() }}

            </small>

            @php
    // Cek jika saat ini diakses lewat customer area (prefix URL mengandung '/c/')
    $isCustomerArea = request()->is('c/*');

    if ($isCustomerArea && isset($company)) {
        // Mengarah ke Customer Area menggunakan parameter token & purchase id
        // Menyesuaikan dengan parameter method showByToken(Request $request, string $token, PurchaseHeader $purchase)
        $detailUrl = route('company.purchase.show', [
            'token'    => $company->access_token,
            'purchase' => $purchase->id,
        ]);
    } else {
        // Mengarah ke Admin Area (Gunakan rute internal admin Anda yang asli)
        // Karena di admin menggunakan Route::get('/company/{company}', ...) 
        // Anda bisa sesuaikan di bawah ini jika admin memiliki rute spesifik sendiri
        $detailUrl = route('admin.company.dashboard', [
            'company' => $purchase->company_id
        ]);
    }
@endphp

<a href="{{ $detailUrl }}" class="button btn-green">
    Lihat Nota
</a>

        </div>

    </td>

</tr>

@empty

<tr>

    <td colspan="{{ empty($company) ? 8 : 6 }}">

        <div class="empty-state">

            <div style="font-size:60px;margin-bottom:10px;">

                📂

            </div>

            <h3 style="margin-bottom:10px;">

                Belum ada transaksi pembelian

            </h3>

            <p>

                Nota hasil scan akan muncul pada halaman ini.

            </p>

        </div>

    </td>

</tr>

@endforelse
            </tbody>

        </table>

    </div>

    {{-- Pagination --}}
    @if(method_exists($purchases,'links'))

        <div style="margin-top:20px;">
            {{ $purchases->links() }}
        </div>

    @endif

    {{-- Logout (khusus admin) --}}
    @if(empty($company))

        <div style="margin-top:24px;text-align:right;">

            <form method="POST"
                  action="{{ route('logout') }}">

                @csrf

                <button
                    type="submit"
                    class="button btn-red">

                    Logout

                </button>

            </form>

        </div>

    @endif

</div>

@endsection