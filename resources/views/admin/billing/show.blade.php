@extends('layouts.app')

@section('title', 'Invoice Detail')

@section('content')

<div class="container py-4">

    {{-- ==========================================================
        HEADER
    ========================================================== --}}

    <div class="page-header">

        <div>

            <h2>Invoice Detail</h2>

            <p>
                Detail transaksi Membership CoreERP.
            </p>

        </div>

        <div>

            <a
                href="{{ route('admin.billing.index') }}"
                class="button btn-secondary">

                ← Billing Center

            </a>

        </div>

    </div>


    {{-- ==========================================================
        STATUS
    ========================================================== --}}

    <div class="status-banner">

        @switch($order->status)

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
                    {{ strtoupper($order->status) }}
                </span>

        @endswitch

    </div>


    {{-- ==========================================================
        INVOICE
    ========================================================== --}}

    <div class="detail-card">

        <h3>
            Invoice Information
        </h3>

        <table class="detail-table">

            <tr>

                <th width="220">
                    Invoice Number
                </th>

                <td>
                    {{ $order->invoice_number }}
                </td>

            </tr>

            <tr>

                <th>
                    Company
                </th>

                <td>
                    {{ $order->company?->name ?? '-' }}
                </td>

            </tr>

            <tr>

                <th>
                    Membership Package
                </th>

                <td>

                    <span class="badge badge-{{ strtolower($order->package) }}">

                        {{ strtoupper($order->package) }}

                    </span>

                </td>

            </tr>

            <tr>

                <th>
                    Amount
                </th>

                <td>

                    <strong>

                        Rp {{ number_format($order->amount,0,',','.') }}

                    </strong>

                </td>

            </tr>

            <tr>

                <th>
                    Currency
                </th>

                <td>

                    {{ $order->currency }}

                </td>

            </tr>

            <tr>

                <th>
                    Payment Gateway
                </th>

                <td>

                    {{ $order->payment_gateway ?? '-' }}

                </td>

            </tr>

            <tr>

                <th>
                    External ID
                </th>

                <td>

                    {{ $order->external_id ?? '-' }}

                </td>

            </tr>

            <tr>

                <th>
                    Created At
                </th>

                <td>

                    {{ $order->created_at?->format('d M Y H:i') }}

                </td>

            </tr>

            <tr>

                <th>
                    Expired At
                </th>

                <td>

                    {{ $order->expires_at?->format('d M Y H:i') ?? '-' }}

                </td>

            </tr>

            <tr>

                <th>
                    Paid At
                </th>

                <td>

                    {{ $order->paid_at?->format('d M Y H:i') ?? '-' }}

                </td>

            </tr>

            <tr>

                <th>
                    Notes
                </th>

                <td>

                    {{ $order->notes ?? '-' }}

                </td>

            </tr>

        </table>

    </div>


    {{-- ==========================================================
        ACTION
    ========================================================== --}}

    <div class="action-bar">

        @if($order->payment_url && $order->status == 'PENDING')

            <a
                href="{{ $order->payment_url }}"
                target="_blank"
                class="button btn-primary">

                Open Payment Page

            </a>

        @endif

        <a
            href="{{ route('admin.billing.index') }}"
            class="button btn-secondary">

            Back

        </a>

    </div>

</div>

@endsection
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

.status-banner{
margin-bottom:24px;
}

.detail-card{
background:#fff;
border:1px solid #e2e8f0;
border-radius:18px;
padding:26px;
margin-bottom:24px;
}

.detail-card h3{
margin:0 0 20px;
font-size:22px;
font-weight:700;
}

.detail-table{
width:100%;
border-collapse:collapse;
}

.detail-table tr{
border-bottom:1px solid #f1f5f9;
}

.detail-table tr:last-child{
border-bottom:none;
}

.detail-table th{
width:220px;
padding:15px 10px;
text-align:left;
vertical-align:top;
font-weight:700;
color:#334155;
background:#f8fafc;
}

.detail-table td{
padding:15px;
vertical-align:top;
color:#0f172a;
}

.badge{
display:inline-flex;
align-items:center;
justify-content:center;
padding:6px 12px;
border-radius:999px;
font-size:12px;
font-weight:700;
letter-spacing:.5px;
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
padding:8px 14px;
border-radius:999px;
font-size:13px;
font-weight:700;
letter-spacing:.5px;
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

.action-bar{
display:flex;
gap:12px;
flex-wrap:wrap;
}

.button{
display:inline-flex;
align-items:center;
justify-content:center;
padding:10px 18px;
border-radius:10px;
text-decoration:none;
font-size:14px;
font-weight:600;
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

@media(max-width:768px){

.page-header{
align-items:flex-start;
}

.detail-card{
padding:18px;
}

.detail-table,
.detail-table tbody,
.detail-table tr,
.detail-table th,
.detail-table td{
display:block;
width:100%;
}

.detail-table th{
background:#f8fafc;
border-bottom:none;
padding-bottom:6px;
}

.detail-table td{
padding-top:0;
padding-bottom:16px;
}

.action-bar{
flex-direction:column;
}

.action-bar .button{
width:100%;
}

}

</style>