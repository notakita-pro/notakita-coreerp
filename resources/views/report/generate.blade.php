@extends('layouts.app')

@section('content')
@php
    $token = request()->route('token');

    $isCustomer = !empty($token);

    $backRoute = $isCustomer
        ? route('company.report.index', ['token' => $token])
        : route('admin.company.report.index', $company);

    $actionRoute = $isCustomer
        ? route('company.report.generate', ['token' => $token])
        : route('admin.company.report.generate', $company);

    $excelRoute = $isCustomer
        ? route('company.report.excel', [
            'token' => $token,
            'from'  => $from,
            'to'    => $to,
        ])
        : route('admin.company.report.excel', [
            'company' => $company,
            'from'    => $from,
            'to'      => $to,
        ]);

    $pdfRoute = $isCustomer
        ? route('company.report.pdf', [
            'token' => $token,
            'from'  => $from,
            'to'    => $to,
        ])
        : route('admin.company.report.pdf', [
            'company' => $company,
            'from'    => $from,
            'to'      => $to,
        ]);
@endphp


<div class="header-container">
    <b>Rekapitulasi Belanja</b>

    <a class="button btn-blue"
       href="{{ $backRoute }}">
        Kembali
    </a>
</div>

<div class="page-header">
    <h2 class="page-title"
        style="display:flex;justify-content:space-between;align-items:center;width:100%;">
    </h2>
</div>

<div class="card">
    {{-- Deteksi apakah URL saat ini mengandung token khusus customer --}}

    <form
    method="POST"
    action="{{ $actionRoute }}"
    class="report-filter">

        @csrf

        <div class="form-row">

            <div>
                <label>Dari Tanggal</label>

                <input
                    type="date"
                    name="from"
                    value="{{ $from }}"
                    required>
            </div>

            <div>
                <label style="text-align:right;">
                    Sampai Dengan
                </label>

                <input
                    type="date"
                    name="to"
                    value="{{ $to }}"
                    required>
            </div>

        </div>

        <div class="button-group">

            <button
                type="submit"
                class="button"
                style="background:#7c7ced;">
                <b>TAMPILKAN</b>
            </button>

            @if($summary)

                <a
                    class="button btn-green"
                   href="{{ $excelRoute }}">
                     To Excel
                </a>

                <a
                    class="button btn-red"
                   href="{{ $pdfRoute }}">
                    To PDF
                </a>

            @endif

        </div>

    </form>

</div>

@if($summary)

<div class="summary-grid">

    <div class="summary-card">

        <div class="summary-title">
            Jumlah Nota
        </div>

        <div class="summary-value">
            {{ number_format($summary['transactions']) }}
        </div>

    </div>

    <div class="summary-card">

        <div class="summary-title">
            Supplier
        </div>

        <div class="summary-value">
            {{ number_format($summary['suppliers']) }}
        </div>

    </div>

    <div class="summary-card">

        <div class="summary-title">
            Total Item
        </div>

        <div class="summary-value">
            {{ number_format($summary['items']) }}
        </div>

    </div>

    <div class="summary-card-2">

        <div class="summary-title">
            <b>Total Belanja (Rp)</b>
        </div>

        <div class="summary-value">
            {{ number_format($summary['total'],0,',','.') }}
        </div>

    </div>

</div>

@endif

@if($rows->count())

<div class="report-table">

<table>

    <thead>
        <tr>
            <th>Tanggal</th>
            <th>No. Nota</th>
            <th>Supplier</th>
            <th>Nama Barang</th>
            <th>Satuan</th>
            <th style="text-align:center">Qty</th>
            <th style="text-align:right">Harga</th>
            <th style="text-align:right">Subtotal</th>
        </tr>
    </thead>

    <tbody>

    @foreach($rows as $row)

        <tr>

            <td>{{ optional($row->purchase?->invoice_date)->format('d-m-Y') }}</td>

            <td>{{ $row->purchase?->invoice_number }}</td>

            <td>{{ $row->purchase?->supplier?->name }}</td>

            <td>{{ $row->item?->name }}</td>

            <td>{{ $row->item?->unit }}</td>

            <td style="text-align:center">
                {{ number_format($row->qty,0,',','.') }}
            </td>

            <td style="text-align:right">
                Rp {{ number_format($row->unit_price,0,',','.') }}
            </td>

            <td style="text-align:right">
                Rp {{ number_format($row->total_price,0,',','.') }}
            </td>

        </tr>

    @endforeach

    </tbody>

</table>

</div>

@elseif($summary)

<div class="empty-state">
    Tidak ada transaksi pada periode tersebut.
</div>

@endif

<style>

.card{
    background:#606060;
    padding:12px;
    margin-bottom:20px;
    border-radius:15px;
}

input[type="date"]{
    font-size:16px;
    background:#ffffed;
}

.report-filter{
    display:flex;
    flex-direction:column;
    gap:20px;
}

.form-row{
    display:flex;
    gap:15px;
    flex-wrap:wrap;
    padding:6px 10px;
    border:1px solid #ffffff69;
    border-radius:10px;
    background:#0000003d;
}

.form-row>div{
    flex:1;
    min-width:100px;
}

.form-row label{
    display:block;
    font-weight:600;
    margin-bottom:6px;
    color:#fff;
    padding:5px;
}

.form-row input{
    width:100%;
    padding:10px;
}

.button-group{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
}

.summary-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(140px,1fr));
    gap:10px;
}

.summary-card{
    background:#fff;
    padding:10px;
    border:2px solid #ddd;
    border-radius:10px;
    text-align:center;
}

.summary-card-2{
    background:#ffffde;
    padding:8px;
    border:2px solid #ddd;
    border-radius:10px;
    text-align:center;
}

.summary-title{
    font-size:13px;
    color:#666;
    margin-bottom:8px;
}

.summary-value{
    font-size:22px;
    font-weight:bold;
    color:#2563eb;
}

.report-table{
    width:100%;
    overflow-x:auto;
    margin-top:25px;
}

.report-table table{
    width:100%;
    border-collapse:collapse;
    background:#fff;
    border:1px solid #e5e7eb;
    border-radius:10px;
    overflow:hidden;
}

.report-table thead{
    background:#2563eb;
    color:#fff;
}

.report-table th{
    padding:14px 16px;
    text-align:left;
    font-size:14px;
    font-weight:600;
}

.report-table td{
    padding:8px 10px;
    border-top:1px solid #8b8b8b;
}

.report-table tbody tr:nth-child(even){
    background:#f8fafc;
}

.report-table tbody tr:hover{
    background:#eef4ff;
}

.empty-state{
    margin-top:25px;
    padding:30px;
    text-align:center;
    border:1px dashed #d1d5db;
    border-radius:10px;
    background:#fffdef;
    color:#de3e3e;
}

</style>

@endsection