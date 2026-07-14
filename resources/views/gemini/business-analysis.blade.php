@extends('layouts.app')

@section('content')

<div class="header-container">

    <h2>🤖 AI Business Advisor</h2>

    <a
        href="{{ route('company.report', $company->access_token) }}"
        class="button btn-grey1">

        Kembali

    </a>

</div>

<div class="ai-header">

    <div class="ai-avatar">

        🤖

    </div>

    <div>

        <div class="ai-title">

            CoreERP AI Analyst

        </div>

        <div class="ai-subtitle">

            Analisis otomatis berdasarkan seluruh data perusahaan Anda.

        </div>

    </div>

</div>



{{-- ====================================================== --}}
{{-- Snapshot --}}
{{-- ====================================================== --}}

<div class="snapshot-grid">

    <div class="snapshot-card">

        <div class="snapshot-label">
            Total Pembelian
        </div>

        <div class="snapshot-value">

            Rp {{ number_format($dashboard['finance']['purchase'] ?? 0,0,',','.') }}

        </div>

    </div>

    <div class="snapshot-card">

        <div class="snapshot-label">
            Supplier
        </div>

        <div class="snapshot-value">

            {{ number_format($dashboard['wallet']['suppliers'] ?? 0) }}

        </div>

    </div>

    <div class="snapshot-card">

        <div class="snapshot-label">
            Produk
        </div>

        <div class="snapshot-value">

            {{ number_format($dashboard['wallet']['items'] ?? 0) }}

        </div>

    </div>

    <div class="snapshot-card">

        <div class="snapshot-label">
            Transaksi
        </div>

        <div class="snapshot-value">

            {{ number_format($dashboard['wallet']['transactions'] ?? 0) }}

        </div>

    </div>

</div>



{{-- ====================================================== --}}
{{-- AI RESULT --}}
{{-- ====================================================== --}}

<div class="analysis-card">

    <div class="analysis-title">

        📋 Ringkasan AI

    </div>

    <div class="analysis-content">

        @if(!empty($analysis))

            {!! nl2br(e($analysis)) !!}

        @else

            AI belum memberikan analisis.

        @endif

    </div>

</div>



<div class="disclaimer">

    ⚠️ Analisis ini dihasilkan oleh AI berdasarkan data yang tersedia di CoreERP.
    Selalu gunakan pertimbangan bisnis sebelum mengambil keputusan.

</div>



<style>

.ai-header{

display:flex;

align-items:center;

gap:18px;

margin-bottom:25px;

}

.ai-avatar{

width:72px;

height:72px;

border-radius:50%;

background:linear-gradient(135deg,#4f46e5,#2563eb);

display:flex;

align-items:center;

justify-content:center;

font-size:34px;

color:#fff;

box-shadow:0 10px 25px rgba(79,70,229,.25);

}

.ai-title{

font-size:24px;

font-weight:700;

color:#1e293b;

}

.ai-subtitle{

margin-top:5px;

color:#64748b;

}



.snapshot-grid{

display:grid;

grid-template-columns:repeat(auto-fit,minmax(180px,1fr));

gap:15px;

margin-bottom:30px;

}

.snapshot-card{

background:#fff;

border-radius:16px;

padding:18px;

border:1px solid #dbeafe;

box-shadow:0 6px 16px rgba(0,0,0,.05);

text-align:center;

}

.snapshot-label{

font-size:13px;

color:#64748b;

margin-bottom:8px;

}

.snapshot-value{

font-size:24px;

font-weight:bold;

color:#2563eb;

}



.analysis-card{

background:#fff;

border-radius:18px;

padding:24px;

border:1px solid #dbeafe;

box-shadow:0 10px 24px rgba(0,0,0,.05);

}

.analysis-title{

font-size:20px;

font-weight:bold;

margin-bottom:20px;

}

.analysis-content{

font-size:15px;

line-height:1.9;

white-space:pre-wrap;

color:#334155;

}



.disclaimer{

margin-top:20px;

padding:18px;

border-radius:14px;

background:#fff8db;

border:1px solid #fde68a;

font-size:13px;

color:#92400e;

}



@media(max-width:768px){

.ai-header{

align-items:flex-start;

}

.ai-avatar{

width:58px;

height:58px;

font-size:28px;

}

.ai-title{

font-size:20px;

}

.snapshot-grid{

grid-template-columns:repeat(2,1fr);

}

.snapshot-value{

font-size:18px;

}

.analysis-card{

padding:18px;

}

.analysis-content{

font-size:14px;

line-height:1.8;

}

}

</style>

@endsection