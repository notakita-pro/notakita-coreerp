@extends('layouts.app')

@section('title', 'Billing Center')

@section('content')

<div class="container py-4">

    {{-- ==========================================================
        HEADER
    ========================================================== --}}
    <div class="page-header">

        <div>

            <h2>Billing Center</h2>

            <p>
                Monitoring seluruh invoice membership, pembayaran dan revenue CoreERP.
            </p>

        </div>

        <div>

            <a
                href="{{ route('admin.dashboard') }}"
                class="button btn-secondary">

                ← Admin Center

            </a>

        </div>

    </div>


    {{-- ==========================================================
        SUMMARY
    ========================================================== --}}

    <div class="summary-grid">

        <div class="summary-card">

            <span class="summary-number text-warning">
                {{ $summary['pending'] }}
            </span>

            <span class="summary-title">
                Pending
            </span>

        </div>

        <div class="summary-card">

            <span class="summary-number text-success">
                {{ $summary['paid'] }}
            </span>

            <span class="summary-title">
                Paid
            </span>

        </div>

        <div class="summary-card">

            <span class="summary-number text-danger">
                {{ $summary['expired'] }}
            </span>

            <span class="summary-title">
                Expired
            </span>

        </div>

        <div class="summary-card">

            <span class="summary-number text-muted">
                {{ $summary['cancelled'] }}
            </span>

            <span class="summary-title">
                Cancelled
            </span>

        </div>

        <div class="summary-card">

            <span class="summary-number text-primary">
                Rp {{ number_format($summary['revenue'],0,',','.') }}
            </span>

            <span class="summary-title">
                Revenue
            </span>

        </div>

    </div>


    {{-- ==========================================================
        BILLING TABLE
    ========================================================== --}}

    <div class="card">

        <div class="card-header">

            <h3>
                Membership Invoice
            </h3>

        </div>

        <div class="table-responsive">

            <table class="table">

                <thead>

                    <tr>

                        <th width="60">
                            #
                        </th>

                        <th>
                            Invoice
                        </th>

                        <th>
                            Company
                        </th>

                        <th width="120">
                            Package
                        </th>

                        <th width="140">
                            Amount
                        </th>

                        <th width="130">
                            Status
                        </th>

                        <th width="150">
                            Expired
                        </th>

                        <th width="180">
                            Action
                        </th>

                    </tr>

                </thead>

                <tbody>

                @forelse($orders as $order)

                    @php

                        $status = strtoupper($order->status);

                    @endphp

                    <tr>

                        <td>

                            {{ $loop->iteration + (($orders->currentPage()-1) * $orders->perPage()) }}

                        </td>

                        <td>

                            <strong>

                                {{ $order->invoice_number }}

                            </strong>

                        </td>

                        <td>

                            {{ $order->company?->name ?? '-' }}

                        </td>

                        <td>

                            <span class="badge badge-{{ strtolower($order->package) }}">

                                {{ strtoupper($order->package) }}

                            </span>

                        </td>

                        <td>

                            Rp {{ number_format($order->amount,0,',','.') }}

                        </td>

                        <td>

                            @switch($status)

                                @case('PAID')

                                    <span class="status status-paid">

                                        PAID

                                    </span>

                                    @break

                                @case('PENDING')

                                    <span class="status status-pending">

                                        PENDING

                                    </span>

                                    @break

                                @case('EXPIRED')

                                    <span class="status status-expired">

                                        EXPIRED

                                    </span>

                                    @break

                                @default

                                    <span class="status status-cancelled">

                                        {{ $status }}

                                    </span>

                            @endswitch

                        </td>

                        <td>

                            {{ optional($order->expires_at)->format('d M Y H:i') }}

                        </td>

                        <td>

                            <div class="action-group">

                                @if($order->payment_url && $status == 'PENDING')

                                    <a
                                        href="{{ $order->payment_url }}"
                                        target="_blank"
                                        class="button btn-primary btn-sm">

                                        Bayar

                                    </a>

                                @endif

                                <a
                                    href="{{ route('admin.billing.show',$order) }}"
                                    class="button btn-secondary btn-sm">

                                    Detail

                                </a>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="8"
                            class="text-center">

                            Belum ada invoice membership.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>


    <div class="mt-4">

        {{ $orders->links() }}

    </div>

</div>

<style>

.page-header{
display:flex;
justify-content:space-between;
align-items:center;
gap:20px;
flex-wrap:wrap;
margin-bottom:28px;
}

.page-header h2{
margin:0;
font-size:30px;
font-weight:700;
}

.page-header p{
margin-top:6px;
color:#64748b;
}

.summary-grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
gap:18px;
margin-bottom:30px;
}

.summary-card{
background:#fff;
border:1px solid #e2e8f0;
border-radius:18px;
padding:22px;
text-align:center;
transition:.25s;
}

.summary-card:hover{
transform:translateY(-3px);
box-shadow:0 10px 28px rgba(0,0,0,.08);
}

.summary-number{
display:block;
font-size:28px;
font-weight:700;
margin-bottom:6px;
}

.summary-title{
font-size:14px;
color:#64748b;
}

.text-primary{
color:#2563eb;
}

.text-success{
color:#16a34a;
}

.text-warning{
color:#d97706;
}

.text-danger{
color:#dc2626;
}

.text-muted{
color:#64748b;
}

.card{
background:#fff;
border:1px solid #e2e8f0;
border-radius:18px;
overflow:hidden;
}

.card-header{
padding:20px 24px;
border-bottom:1px solid #e2e8f0;
display:flex;
justify-content:space-between;
align-items:center;
}

.card-header h3{
margin:0;
font-size:20px;
font-weight:700;
}

.table-responsive{
overflow-x:auto;
}

.table{
width:100%;
border-collapse:collapse;
}

.table th{
background:#f8fafc;
padding:15px;
text-align:left;
font-size:14px;
font-weight:700;
border-bottom:1px solid #e2e8f0;
white-space:nowrap;
}

.table td{
padding:15px;
border-bottom:1px solid #f1f5f9;
vertical-align:middle;
}

.table tbody tr:hover{
background:#fafcff;
}

.badge{
display:inline-flex;
align-items:center;
justify-content:center;
padding:6px 12px;
border-radius:999px;
font-size:12px;
font-weight:700;
letter-spacing:.4px;
}

.badge-free{
background:#e5e7eb;
color:#475569;
}

.badge-silver{
background:#e2e8f0;
color:#334155;
}

.badge-gold{
background:#fef3c7;
color:#b45309;
}

.status{
display:inline-flex;
align-items:center;
justify-content:center;
padding:6px 12px;
border-radius:999px;
font-size:12px;
font-weight:700;
}

.status-paid{
background:#dcfce7;
color:#15803d;
}

.status-pending{
background:#fef3c7;
color:#b45309;
}

.status-expired{
background:#fee2e2;
color:#b91c1c;
}

.status-cancelled{
background:#e5e7eb;
color:#475569;
}

.action-group{
display:flex;
gap:8px;
flex-wrap:wrap;
}

.button{
display:inline-flex;
align-items:center;
justify-content:center;
padding:9px 16px;
border-radius:10px;
font-size:13px;
font-weight:600;
text-decoration:none;
transition:.2s;
}

.button:hover{
transform:translateY(-1px);
}

.btn-primary{
background:#2563eb;
color:#fff;
}

.btn-primary:hover{
background:#1d4ed8;
}

.btn-secondary{
background:#eef2ff;
color:#2563eb;
}

.btn-secondary:hover{
background:#dbeafe;
}

.btn-sm{
padding:8px 14px;
font-size:13px;
}

.text-center{
text-align:center;
}

.mt-4{
margin-top:24px;
}

@media(max-width:768px){

.page-header{
align-items:flex-start;
}

.summary-grid{
grid-template-columns:repeat(2,1fr);
}

.card-header{
flex-direction:column;
align-items:flex-start;
gap:12px;
}

.table th,
.table td{
padding:12px;
font-size:13px;
}

.action-group{
flex-direction:column;
}

.action-group .button{
width:100%;
}

}

@media(max-width:520px){

.summary-grid{
grid-template-columns:1fr;
}

.page-header h2{
font-size:24px;
}

}

</style>

@endsection