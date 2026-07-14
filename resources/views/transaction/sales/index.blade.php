@extends('layouts.app')

@section('content')

@php

    $token = request()->route('token');

    $isCustomer = !empty($token);

    $backRoute = $isCustomer
        ? route('company.transaction', ['token' => $token])
        : route('admin.company.transaction', $company);

    $createRoute = $isCustomer
        ? route('company.sales.create', ['token' => $token])
        : route('admin.company.sales.create', $company);

    $statusMap = [

        'paid' => [
            'class' => 'badge-success',
            'label' => 'Lunas',
        ],

        'partial' => [
            'class' => 'badge-warning',
            'label' => 'Sebagian',
        ],

        'unpaid' => [
            'class' => 'badge-danger',
            'label' => 'Belum Bayar',
        ],

    ];

@endphp

<div class="container">

    <div class="page-header">

        <div>

            <h2 class="page-title">
                Transaksi Penjualan
            </h2>

            <p class="page-description">
                Kelola seluruh invoice penjualan perusahaan.
            </p>

        </div>

    </div>

    <hr class="page-divider">

    {{-- ==========================================================
         RINGKASAN
    ========================================================== --}}

    <div class="summary-grid">

        <div class="summary-card">

            <div class="summary-title">
                Total Invoice
            </div>

            <div class="summary-value">
                {{ number_format($totalInvoice) }}
            </div>

        </div>

        <div class="summary-card">

            <div class="summary-title">
                Customer
            </div>

            <div class="summary-value">
                {{ number_format($totalCustomer) }}
            </div>

        </div>

        <div class="summary-card summary-card-primary">

            <div class="summary-title">
                Total Penjualan
            </div>

            <div class="summary-value">
                Rp {{ number_format($totalSales,0,',','.') }}
            </div>

        </div>

        <div class="summary-card">

            <div class="summary-title">
                Total Piutang
            </div>

            <div class="summary-value">
                Rp {{ number_format($totalReceivable,0,',','.') }}
            </div>

        </div>

    </div>

    {{-- ==========================================================
         TOOLBAR
    ========================================================== --}}

    <div class="toolbar">

    <a
        href="{{ $backRoute }}"
        class="button btn-blue">

        ← Kembali

    </a>

   <button
    type="button"
    id="btnCreateSale"
    class="button btn-green">

    + Penjualan Baru

</button>
    

</div>

    {{-- ==========================================================
         TABLE
    ========================================================== --}}

    <div class="card">

        @if($sales->count())

        <div class="table-responsive">

            <table class="table">

                <thead>

                    <tr>

                        <th width="120">
                            Tanggal
                        </th>

                        <th width="170">
                            Invoice
                        </th>

                        <th>
                            Customer
                        </th>

                        <th style="text-align:center">
                            Sistem
                        </th>

                        <th style="text-align:right">
                            Grand Total
                        </th>

                        <th style="text-align:right">
                            Terbayar
                        </th>

                        <th style="text-align:right">
                            Piutang
                        </th>

                        <th style="text-align:center">
                            Status
                        </th>

                        <th width="100" style="text-align:center">
                            Aksi
                        </th>

                    </tr>

                </thead>

                <tbody>

                @foreach($sales as $sale)

                    @php

                        $editRoute = $isCustomer

                            ? route(
                                'company.sales.edit',
                                [
                                    'token' => $token,
                                    'sale' => $sale,
                                ]
                            )

                            : route(
                                'admin.company.sales.edit',
                                [
                                    'company' => $company,
                                    'sale' => $sale,
                                ]
                            );

                        $status = $statusMap[$sale->payment_status]

                            ?? [

                                'class' => 'badge-secondary',

                                'label' => '-',

                            ];

                    @endphp

                    <tr>

                        <td>

                            {{ optional($sale->invoice_date)->format('d-m-Y') }}

                        </td>

                        <td>

                            <strong>

                                {{ $sale->invoice_number }}

                            </strong>

                        </td>

                        <td>

                            @if($sale->customer)

                                <strong>

                                    {{ $sale->customer->name }}

                                </strong>

                                @if(!empty($sale->customer->address))

                                    <br>

                                    <small class="customer-address">

                                        {{ $sale->customer->address }}

                                    </small>

                                @endif

                            @else

                                <span class="badge badge-secondary">

                                    Customer Umum

                                </span>

                            @endif

                        </td>

                        <td style="text-align:center">

                            @if($sale->payment_term == 'credit')

                                <span class="badge badge-warning">

                                    Tempo

                                </span>

                            @else

                                <span class="badge badge-success">

                                    Tunai

                                </span>

                            @endif

                        </td>

                        <td style="text-align:right">

                            Rp {{ number_format($sale->grand_total,0,',','.') }}

                        </td>

                        <td style="text-align:right">

                            Rp {{ number_format($sale->paid_amount,0,',','.') }}

                        </td>

                        <td style="text-align:right">

                            Rp {{ number_format($sale->balance_due,0,',','.') }}

                        </td>

                        <td style="text-align:center">

                            <span class="badge {{ $status['class'] }}">

                                {{ $status['label'] }}

                            </span>

                        </td>

                        <td style="text-align:center">

                            <a
                                href="{{ $editRoute }}"
                                class="button btn-sm btn-blue">

                                Edit

                            </a>

                        </td>

                    </tr>

                @endforeach

                </tbody>

            </table>

        </div>

        <div class="pagination-wrapper">

            {{ $sales->links() }}

        </div>

        @else

        <div class="empty-state">

            <div class="empty-icon">

                🧾

            </div>

            <h3>

                Belum Ada Invoice Penjualan

            </h3>

            <p>

                Klik tombol
                <strong>Penjualan Baru</strong>
                untuk membuat invoice pertama Anda.

            </p>

            <button
    type="button"
    id="btnCreateSaleEmpty"
    class="button btn-green">

    + Buat Invoice

</button>

        </div>

        @endif

    </div>
 {{-- ==========================================================
MODAL PILIH CUSTOMER
========================================================== --}}


    
    

</div>

<style>
.page-divider{

    border:0;

    border-top:1px solid var(--border-color);

    margin:20px 0;

}

.summary-grid{

    display:grid;

    grid-template-columns:repeat(auto-fit,minmax(180px,1fr));

    gap:15px;

    margin-bottom:20px;

}

.summary-card{

    background:#fff;

    border:1px solid var(--border-color);

    border-radius:12px;

    padding:18px;

    box-shadow:var(--shadow-sm);

}

.summary-card-primary{

    background:#eef6ff;

}

.summary-title{

    font-size:13px;

    color:var(--text-muted);

    margin-bottom:8px;

}

.summary-value{

    font-size:24px;

    font-weight:700;

}

.toolbar{

    display:flex;

    justify-content:space-between;

    align-items:center;

    gap:10px;

    margin-bottom:20px;

    flex-wrap:wrap;

}

.table-responsive{

    overflow-x:auto;

}

.table{

    width:100%;

    border-collapse:collapse;

}

.table thead{

    background:#f8fafc;

}

.table th{

    padding:12px;

    border-bottom:1px solid var(--border-color);

    font-size:14px;

    text-align:left;

    white-space:nowrap;

}

.table td{

    padding:12px;

    border-bottom:1px solid #eee;

    vertical-align:middle;

}

.table tbody tr:hover{

    background:#fafcff;

}

.customer-address{

    display:block;

    margin-top:4px;

    color:#777;

    font-size:12px;

}

.badge{

    display:inline-flex;

    align-items:center;

    justify-content:center;

    padding:5px 10px;

    border-radius:999px;

    font-size:12px;

    font-weight:600;

    white-space:nowrap;

}

.badge-success{

    background:#dcfce7;

    color:#166534;

}

.badge-warning{

    background:#fef3c7;

    color:#92400e;

}

.badge-danger{

    background:#fee2e2;

    color:#991b1b;

}

.badge-secondary{

    background:#ececec;

    color:#555;

}

.btn-sm{

    padding:6px 12px;

    font-size:13px;

}

.pagination-wrapper{

    margin-top:20px;

}

.empty-state{

    text-align:center;

    padding:60px 20px;

}

.empty-icon{

    font-size:60px;

    margin-bottom:18px;

}

.empty-state h3{

    margin-bottom:10px;

}

.empty-state p{

    color:var(--text-muted);

    margin-bottom:20px;

}

@media(max-width:768px){

    .summary-grid{

        grid-template-columns:1fr 1fr;

    }

    .toolbar{

        flex-direction:column;

        align-items:stretch;

    }

    .toolbar .button{

        width:100%;

        text-align:center;

    }

    .table{

        min-width:980px;

    }

}

.modal-overlay{

    position:fixed;

    inset:0;

    background:rgba(0,0,0,.45);

    display:none;

    align-items:center;

    justify-content:center;

    z-index:9999;

}

.modal-overlay.show{

    display:flex;

}

.modal-card{

    width:95%;

    max-width:520px;

    background:#fff;

    border-radius:18px;

    overflow:hidden;

    box-shadow:0 20px 50px rgba(0,0,0,.2);

}

.modal-header{

    display:flex;

    justify-content:space-between;

    align-items:center;

    padding:18px 22px;

    border-bottom:1px solid #eee;

}

.modal-header h3{

    margin:0;

}

.modal-header button{

    background:none;

    border:none;

    font-size:22px;

    cursor:pointer;

}

.modal-body{

    padding:22px;

}

.modal-body select,
.modal-body input{

    width:100%;

    padding:10px 12px;

}

.modal-footer{

    padding:18px 22px;

    border-top:1px solid #eee;

    text-align:right;

}

</style>

@push('scripts')
<script src="{{ asset('js/components/customer-modal.js') }}"></script>
<script>
    window.salesCreateRoute = @json($createRoute);
</script>
@endpush
@include('transaction.sales.components.customer-modal')
@endsection