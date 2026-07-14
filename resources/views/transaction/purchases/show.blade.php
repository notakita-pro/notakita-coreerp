@extends('layouts.app')

@section('content')
<div class="show-container">

    {{-- Header Halaman --}}
    <div class="page-header">
        <div class="header-main-flex">
            <div>
                <h6 class="page-title">
                    Detail Invoice <span class="highlight">#{{ $purchase->id }}</span>
                </h6>
            </div>
            
            {{-- Tombol Hapus diletakkan di atas (Standar ERP Profesional) --}}
            <div class="header-action-destructive">
                @php
                    // Cek apakah halaman ini sedang diakses via Customer Area (WA + PIN)
                    $isCustomerArea = request()->is('c/*');

                    if ($isCustomerArea && isset($company)) {
                        // Mengarah ke URL: /c/{token}/purchase/{purchase}
                        $deleteUrl = route('company.purchase.destroy', [
                            'token'    => $company->access_token,
                            'purchase' => $purchase->id
                        ]);
                    } else {
                        // Mengarah ke URL Admin internal: /admin/company/{company}/purchase/{purchase}
                        $deleteUrl = route('admin.company.purchase.destroy', [
                            'company'  => $purchase->company_id,
                            'purchase' => $purchase->id
                        ]);
                    }
                @endphp

                <form action="{{ $deleteUrl }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus nota transaksi ini? Tindakan ini tidak dapat dibatalkan.');" style="display: inline-block;">
                    @csrf
                    @method('DELETE')
                    
                    <button type="submit" class="btn btn-danger">
                        <i class="fa fa-trash"></i> Hapus Nota
                    </button>
                </form>
            </div>
        </div>
    </div>

    @if(!$purchase)
        <div class="error-box">
            ⚠️ Data tidak ditemukan atau telah dihapus.
        </div>
    @else

        {{-- Panel Warning AI --}}
        @if($purchase->has_warning)
            <div class="validation-box">
                <div class="validation-icon">⚠️</div>
                <div class="validation-text">
                    <h3>Pemeriksaan Disarankan</h3>
                    <p>
                        AI menemukan <b>{{ $purchase->warning_count }}</b> ketidaksesuaian pada hasil pembacaan nota.
                    </p>
                </div>
            </div>
        @endif

        {{-- Success Message --}}
        @if(session('success'))
            <div class="success-box">
                ✅ {{ session('success') }}
            </div>
        @endif

        {{-- Header Informasi --}}
        <div class="info-section">
            <div class="info-grid">
                <div class="info-group">
                    <span class="info-label">Tanggal Invoice</span>
                    <span class="info-value">
                        {{ optional($purchase->invoice_date)->format('d-m-Y') }}
                    </span>
                </div>

                <div class="info-group">
                    <span class="info-label">Supplier</span>
                    <span class="info-value font-semibold">
                        {{ $purchase->supplier?->name ?? 'UNKNOWN' }}
                    </span>
                </div>

                <div class="info-group total-highlight-box">
                    <span class="info-label">Total Pembelian</span>
                    <span class="info-value price-grand-total {{ $purchase->has_warning ? 'text-danger' : '' }}">
                        Rp {{ number_format($purchase->total, 0, ',', '.') }}
                    </span>

                    @if($purchase->has_warning)
                        <div class="warning-meta">
                            <small class="warning-small">
                                ⚠ Seharusnya <b>Rp {{ number_format($purchase->calculated_total, 0, ',', '.') }}</b>
                            </small>
                            <small class="danger-small">
                                Selisih Rp {{ number_format($purchase->difference, 0, ',', '.') }}
                            </small>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Panel Nota Asli dengan Kontrol di Dalam Komponen Gambar --}}
        <div class="receipt-panel">
            <button class="receipt-toggle" onclick="toggleReceipt()">
                <span>
                    @if($purchase->has_warning)
                        ⚠ Lihat Nota Asli
                    @else
                        🧾 Nota Asli
                    @endif
                </span>
                <span id="receiptArrow">▼ Tampilkan</span>
            </button>

            <div id="receiptBody" class="receipt-body" style="display:none;">
                <div class="receipt-container-viewport">
                    
                    {{-- Toolbar Melayang di Dalam Gambar Standar Enterprise ERP --}}
                    <div class="receipt-toolbar-floating">
                        <button type="button" id="zoomIn" class="zoom-btn">+</button>
                        <span id="zoomLevel">100%</span>
                        <button type="button" id="zoomOut" class="zoom-btn">−</button>
                    </div>

                    {{-- Kanvas Utama untuk Gambar dan Fitur Geser --}}
                    <div class="receipt-canvas" id="receiptCanvas">
                        @php
                            if ($isCustomerArea && isset($company)) {
                                $imageUrl = route('company.purchase.receipt.image', [
                                    'token'    => $company->access_token,
                                    'purchase' => $purchase->id
                                ]);
                            } else {
                                $imageUrl = route('admin.company.purchase.receipt.image', [
                                    'company'  => $purchase->company_id,
                                    'purchase' => $purchase->id
                                ]);
                            }
                        @endphp
                        <img id="receiptImage" src="{{ $imageUrl }}" class="receipt-image" alt="Nota Asli" draggable="false">
                    </div>

                </div>
            </div>
        </div>

        {{-- Rincian Detail Item --}}
        <div class="info-section">
            <h3 class="section-title" style="background-color: #ebffdf; padding: 5px 20px;">Rincian Item</h3>
            <div class="table-responsive-wrapper">
                <table class="show-table">
                    <thead>
                        <tr>
                            <th width="50" style="text-align:center;">No</th>
                            <th>Nama Item</th>
                            <th width="80" style="text-align:center;">Qty</th>
                            <th width="140" style="text-align:right;">Harga</th>
                            <th width="170" style="text-align:right;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchase->details as $i => $detail)
                            <tr class="{{ !$detail->is_valid ? 'row-warning' : '' }}">
                                <td style="text-align:center; color: #6b7280;">
                                    {{ $i + 1 }}
                                </td>
                                <td>
                                    <strong class="item-name">{{ $detail->item?->name ?? '--' }}</strong>
                                    @if(!$detail->is_valid)
                                        <small class="warning-small" style="margin-top: 4px;">
                                            ⚠ Seharusnya Rp {{ number_format($detail->expected_subtotal, 0, ',', '.') }}
                                        </small>
                                    @endif
                                </td>
                                <td style="text-align:center;">
                                    <span class="badge-qty">{{ (int)$detail->qty }}</span>
                                </td>
                                <td style="text-align:right;">
                                    Rp {{ number_format($detail->unit_price, 0, ',', '.') }}
                                </td>
                                <td style="text-align:right;">
                                    <strong>Rp {{ number_format($detail->total_price, 0, ',', '.') }}</strong>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tombol Aksi Utama (Bawah) --}}
        <div class="form-actions-grid">
            @php
                if ($isCustomerArea && isset($company)) {
                    $backUrl   = route('company.dashboard', ['token' => $company->access_token]);
                    $exportUrl = route('company.purchase.export', ['token' => $company->access_token, 'purchase' => $purchase->id]);
                    $editUrl   = route('company.purchase.edit', ['token' => $company->access_token, 'purchase' => $purchase->id]);
                } else {
                    $backUrl   = route('admin.company.dashboard', ['company' => $purchase->company_id]);
                    $exportUrl = route('admin.company.purchase.export', ['company' => $purchase->company_id, 'purchase' => $purchase->id]);
                    $editUrl   = route('admin.company.purchase.edit', ['company' => $purchase->company_id, 'purchase' => $purchase->id]);
                }
            @endphp

            <a href="{{ $backUrl }}" class="btn-action btn-gray" title="Kembali">
                <b>← Kembali</b>
            </a>
            <a href="{{ $exportUrl }}" class="btn-action btn-green">
                Cetak Excel
            </a>
            <a href="{{ $editUrl }}" class="btn-action btn-blue">
                Edit Nota
            </a>
        </div>
    @endif

</div>

{{-- CSS Styling Terpadu --}}
<style>
    :root {
        --primary-color: #0038bb;
        --border-muted: #e5e7eb;
        --text-main: #1f2937;
        --text-muted: #4b5563;
    }

    .show-container {
        max-width: 950px;
        font-family: system-ui, -apple-system, sans-serif;
        color: var(--text-main);
    }

    /* Header Layout Flex */
    .page-header {
        margin-bottom: 15px;
    }
    .header-main-flex {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
    }
    .page-title {
        font-size: 20px;
        font-weight: 700;
        margin: 0;
    }
    .highlight { color: var(--primary-color); }

    /* Info Section Card */
    .info-section {
        background: #fff;
        border: 1px solid #a9a9a9;
        border-radius: 10px;
        padding: 10px;
        margin-bottom: 10px;
        padding-bottom: 20px;
    }
    .section-title {
        font-size: 14px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-muted);
        margin: 0 0 20px 0;
        padding-bottom: 8px;
        border-bottom: 1px solid var(--border-muted);
    }

    /* Grid Layout */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
    .info-group {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .info-label {
        font-size: 11px;
        text-transform: uppercase;
        font-weight: 600;
        color: #9ca3af;
        letter-spacing: 0.03em;
    }
    .info-value {
        font-size: 15px;
    }
    .font-semibold { font-weight: 600; }

    /* Total Highlight Box */
    .total-highlight-box {
        grid-column: span 2;
        background: #ebf5ff;
        padding: 10px;
        border-radius: 8px;
        border-left: 4px solid var(--primary-color);
    }
    .price-grand-total {
        font-size: 22px;
        font-weight: 800;
    }
    .warning-meta {
        display: flex;
        gap: 16px;
        margin-top: 6px;
    }

    /* Box Status Alerts */
    .validation-box {
        background: #fff7ed;
        border: 1px solid #fdba74;
        border-radius: 12px;
        padding: 16px;
        display: flex;
        gap: 16px;
        margin-bottom: 24px;
    }
    .validation-box h3 { margin: 0 0 4px 0; font-size: 16px; color: #c2410c;}
    .validation-box p { margin: 0; font-size: 14px; color: #7c2d12;}
    .validation-icon { font-size: 28px; line-height: 1; }

    .success-box {
        background: #ecfdf5;
        border: 1px solid #6ee7b7;
        padding: 14px;
        border-radius: 12px;
        margin-bottom: 24px;
        color: #065f46;
        font-weight: 600;
        font-size: 14px;
    }
    .error-box {
        background: #fef2f2;
        border: 1px solid #fca5a5;
        padding: 14px;
        border-radius: 12px;
        color: #991b1b;
        font-weight: 600;
    }

    /* Receipt Panel Base */
    .receipt-panel {
        background: #fff;
        border: 1px solid var(--border-muted);
        border-radius: 12px;
        margin-bottom: 24px;
        overflow: hidden;
    }
    .receipt-toggle {
        width: 100%;
        padding: 16px 24px;
        background: #fff;
        border: none;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .receipt-toggle:hover { background: #f8fafc; }
    
    .receipt-body {
        padding: 0;
        border-top: 1px solid var(--border-muted);
        background: #f1f5f9;
    }

    /* Pengaturan Ketinggian Wadah Gambar */
    .receipt-container-viewport {
        position: relative;
        width: 100%;
        height: 380px;
        overflow: hidden;
        background: #e2e8f0;
    }

    /* Kanvas Gambar yang Dapat Digeser */
    .receipt-canvas {
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: grab;
        overflow: hidden;
        user-select: none;
        touch-action: none; 
    }
    .receipt-canvas:active {
        cursor: grabbing;
    }

    .receipt-image {
        max-width: 100%;
        object-fit: contain;
        transition: transform 0.1s cubic-bezier(0.2, 0, 0, 1);
        transform-origin: center center;
    }

    /* Toolbar Melayang */
    .receipt-toolbar-floating {
        position: absolute;
        top: 6px;
        right: 5px;
        z-index: 10;
        display: flex;
        align-items: center;
        gap: 8px;
        backdrop-filter: blur(2px);
        -webkit-backdrop-filter: blur(8px);
        padding: 6px 5px;
        border-radius: 30px;
        border: 1px solid rgb(0 0 0 / 13%);
    }

    .zoom-btn {
        width: 32px;
        height: 32px;
        border: none;
        border-radius: 50%;
        background: var(--primary-color);
        color: white;
        font-size: 18px;
        cursor: pointer;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.1s ease;
    }
    .zoom-btn:hover { background: #002da0; }
    
    #zoomLevel {
        min-width: 48px;
        text-align: center;
        font-weight: 700;
        font-size: 13px;
        color: var(--text-main);
    }

    /* Table System */
    .table-responsive-wrapper { overflow-x: auto; }
    .show-table { width: 100%; border-collapse: collapse; font-size: 14px; white-space: nowrap; }
    .show-table th {
        background: #f8fafc;
        padding: 12px;
        font-weight: 600;
        color: var(--text-muted);
        border-bottom: 1px solid var(--border-muted);
    }
    .show-table td { padding: 14px 12px; border-bottom: 1px solid var(--border-muted); vertical-align: top; }
    .row-warning { background: #fffbeb; }
    .badge-qty {
        background: #e5e7eb;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
    }

    .warning-small { display: block; color: #b45309; font-size: 12px; font-weight: 500; }
    .danger-small { display: block; color: #dc2626; font-size: 12px; font-weight: 600; }
    .text-danger { color: #dc2626 !important; }

    /* Action Buttons Area (Bawah) */
    .form-actions-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid var(--border-muted);
    }
    
    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 12px 18px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        border: none;
        transition: transform 0.1s ease, background 0.15s ease;
        text-align: center;
    }
    .btn-action:active { transform: scale(0.98); }

    .btn-gray { background: #f3f4f6; color: #374151; border: 1px solid #d1d5db; }
    .btn-gray:hover { background: #e5e7eb; }
    .btn-blue { background: var(--primary-color); color: #fff; }
    .btn-blue:hover { background: #002da0; }
    .btn-green { background: #10b981; color: #fff; }
    .btn-green:hover { background: #059669; }

    /* Responsive Breakdown Mobile */
    @media (max-width: 640px) {
        .header-main-flex {
            flex-direction: column;
            align-items: stretch;
            gap: 12px;
        }
        .info-grid { grid-template-columns: 1fr; }
        .warning-meta { flex-direction: column; gap: 4px; }
        .receipt-container-viewport { height: 300px; }
        .form-actions-grid {
            grid-template-columns: repeat(3, 1fr); 
            gap: 8px;
            padding-bottom: 10px;
        }
    }
</style>

{{-- Toggling, Zooming, and Dragging Script --}}
<script>
    function toggleReceipt() {
        const body = document.getElementById('receiptBody');
        const arrow = document.getElementById('receiptArrow');
        
        if (body.style.display === "none") {
            body.style.display = "block";
            arrow.innerHTML = "▲ Sembunyikan";
        } else {
            body.style.display = "none";
            arrow.innerHTML = "▼ Tampilkan";
        }
    }

    document.addEventListener("DOMContentLoaded", function(){
        const img = document.getElementById("receiptImage");
        const canvas = document.getElementById("receiptCanvas");
        if(!img || !canvas) return;

        const zoomIn = document.getElementById("zoomIn");
        const zoomOut = document.getElementById("zoomOut");
        const zoomText = document.getElementById("zoomLevel");

        let scale = 1.5;
        let isDragging = false;
        let startX, startY, translateX = 0, translateY = 0;

        function refresh(){
            img.style.transform = `translate(${translateX}px, ${translateY}px) scale(${scale})`;
            zoomText.innerHTML = Math.round(scale * 100) + "%";
        }

        zoomIn.onclick = function(e){
            e.stopPropagation();
            if(scale < 4){
                scale += 0.25;
                refresh();
            }
        };

        zoomOut.onclick = function(e){
            e.stopPropagation();
            if(scale > 1){
                scale -= 0.25;
                if(scale === 1) {
                    translateX = 0;
                    translateY = 0;
                }
                refresh();
            }
        };

        // Drag-to-Pan Mouse Event
        canvas.addEventListener('mousedown', (e) => {
            isDragging = true;
            startX = e.clientX - translateX;
            startY = e.clientY - translateY;
            canvas.style.cursor = 'grabbing';
        });

        window.addEventListener('mouseup', () => {
            isDragging = false;
            canvas.style.cursor = 'grab';
        });

        canvas.addEventListener('mousemove', (e) => {
            if (!isDragging) return;
            e.preventDefault();
            translateX = e.clientX - startX;
            translateY = e.clientY - startY;
            refresh();
        });

        // Mobile Dragging
        canvas.addEventListener('touchstart', (e) => {
            if (e.touches.length === 1) {
                isDragging = true;
                startX = e.touches[0].clientX - translateX;
                startY = e.touches[0].clientY - translateY;
            }
        }, { passive: true });

        canvas.addEventListener('touchend', () => {
            isDragging = false;
        });

        canvas.addEventListener('touchmove', (e) => {
            if (!isDragging || e.touches.length !== 1) return;
            e.preventDefault(); 
            translateX = e.touches[0].clientX - startX;
            translateY = e.touches[0].clientY - startY;
            refresh();
        }, { passive: false });
    });
</script>
@endsection