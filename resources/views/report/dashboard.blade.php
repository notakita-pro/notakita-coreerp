@extends('layouts.app')

@section('content')

<div class="header-container" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
    <h2 style="margin: 0; font-size: 20px; font-weight: 700;">Analisis Bisnis Anda</h2>
    <a class="button btn-blue" href="{{ route('company.dashboard', $company->access_token) }}">
        Kembali
    </a>
</div>
<hr>
<br>

{{-- ========================================================= --}}
{{-- Grafik (Coming Soon) --}}
{{-- ========================================================= --}}
<div class="chart-card">
    <div class="chart-title">
        📊 Grafik Performa Bisnis
    </div>
    <div class="chart-placeholder">
        Grafik akan tampil di sini.
        <br><br>
        (Penjualan • Pembelian • Laba)
    </div>
</div>

{{-- PERUBAHAN UTAMA: Kontainer aksi tombol dibuat flex space-between --}}
<div class="report-action">
    <a href="{{ route('company.report.ai', $company->access_token) }}"
    class="button btn-red">
    🤖 AI Business Advisor</a>
    
    <a href="{{ route('company.report.generate', $company->access_token) }}" class="button btn-green">
        📄 Lanjutkan Cetak
    </a>
</div>

{{-- ========================================================= --}}
{{-- Ringkasan --}}
{{-- ========================================================= --}}
<div class="section-title">
    Rekapitulasi Kinerja Selama Ini
</div>

<div class="finance-grid">
    {{-- Loop Data Finansial --}}
    @php
        $cards = [
            ['icon' => '🛒', 'label' => 'Pembelian', 'key' => 'purchase'],
            ['icon' => '💳', 'label' => 'Penjualan', 'key' => 'sales'],
            ['icon' => '💼', 'label' => 'Operasional', 'key' => 'operational_cost'],
            ['icon' => '🧾', 'label' => 'Piutang', 'key' => 'receivable'],
            ['icon' => '🏦', 'label' => 'Utang', 'key' => 'payable'],
            ['icon' => '💰', 'label' => 'Laba Bersih', 'key' => 'net_profit'],
        ];
    @endphp

    @foreach($cards as $card)
        <div class="finance-card">
            <div class="finance-icon">{{ $card['icon'] }}</div>
            <div>
                <div class="finance-value">
                    Rp {{ number_format($dashboard['finance'][$card['key']] ?? 0, 0, ',', '.') }}
                </div>
                <div class="finance-label">{{ $card['label'] }}</div>
            </div>
        </div>
    @endforeach
</div>

<style>
.chart-card {
    background: #fff;
    border-radius: 18px;
    padding: 20px;
    margin-bottom: 30px;
    box-shadow: 0 8px 20px rgba(0,0,0,.05);
}
.chart-title {
    font-weight: 700;
    margin-bottom: 18px;
}
.chart-placeholder {
    height: 240px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: #94a3b8;
    border: 2px dashed #dbeafe;
    border-radius: 14px;
    background: #f8fbff;
}
.section-title {
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 20px;
    text-align: center;
    color: #1e293b;
}
.finance-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 14px;
}
.finance-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #dbeafe;
    padding: 18px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 6px 16px rgba(0,0,0,.05);
}
.finance-icon {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    background: #eef4ff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}
.finance-value {
    font-size: 20px;
    font-weight: bold;
    color: #1e293b;
}
.finance-label {
    color: #64748b;
    font-size: 13px;
}

/* PERUBAHAN CSS: Pengaturan perataan kiri-kanan tombol */
.report-action {
    margin: 25px 0px; /* Diubah menjadi 0px di sisi samping agar sejajar penuh dengan ujung card */
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.report-action .button {
    padding: 16px 24px;
    font-size: 16px;
    font-weight: 600;
    border-radius: 8px;
    text-decoration: none;
    display: inline-block;
}

@media(max-width:768px){
    /* Tambahan proteksi mobile agar tombol tidak rusak bertumpuk */
    .report-action {
        flex-direction: row; /* Tetap berdampingan kiri-kanan di HP jika muat */
        gap: 10px;
    }
    .report-action .button {
        padding: 12px 16px;
        font-size: 14px;
        flex: 1; /* Membuat kedua tombol membagi ruang sama rata di mobile */
        text-align: center;
    }
    .finance-grid {
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 8px;
    }
    .finance-card {
        padding: 12px;
        flex-direction: column;
        text-align: center;
        gap: 8px;
    }
    .finance-value {
        font-size: 16px;
    }
}
</style>

@endsection