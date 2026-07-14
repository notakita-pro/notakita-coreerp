@extends('layouts.app')

@push('styles')
<style>

/* ==========================================================
   TRANSACTION PAGE
========================================================== */

.transaction-page{
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
}

.transaction-tab{
    flex:1;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:8px;
    padding:14px;
    border-radius:14px;
    text-decoration:none;
    font-weight:700;
    border:1px solid var(--border-color);
    background:#fff;
    color:#475569;
    transition:.25s;
}

.transaction-tab:hover{
    background:#eef2ff;
}

.transaction-tab.active{
    background:linear-gradient(135deg,var(--primary),#2563eb);
    color:#fff;
    border-color:transparent;
    box-shadow:0 8px 18px rgba(37,99,235,.18);
}

.transaction-tab.inactive{
    background:#fff;
}

.transaction-tab small{
    display:block;
    font-size:11px;
    font-weight:500;
    opacity:.8;
}

/* ==========================================================
   CONTENT
========================================================== */

.transaction-content{
    background:#fff;
    border-radius:18px;
    border:1px solid var(--border-color);
    box-shadow:var(--shadow-sm);
    overflow:hidden;
}

/* ==========================================================
   MOBILE
========================================================== */

@media(max-width:768px){

.transaction-tabs{
    gap:10px;
}

.transaction-tab{
    padding:13px 8px;
    font-size:15px;
    flex-direction:column;
}

.transaction-tab small{
    margin-top:2px;
}

}

</style>
@endpush

@section('content')

<div class="transaction-page">

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
           class="transaction-tab {{ $activeTab=='purchase' ? 'active' : 'inactive' }}">

            🧾 Belanja

        </a>

        <a href="{{ route('company.sales.index',$company->access_token) }}"
           class="transaction-tab {{ $activeTab=='sales' ? 'active' : 'inactive' }}">

            💰 Penjualan

            <small>Coming Soon</small>

        </a>

    </div>

    <div class="transaction-content">

        @if($activeTab=='purchase')

            @include('transaction.purchases.partials.table')

        @else

            @include('transaction.sales.index')

        @endif

    </div>

</div>

@endsection