@extends('layouts.app')

@section('content')
<div class="edit-container">
    
    <div class="page-header">
        <h2 class="page-title">Form Perbaikan Data - <span class="highlight">#{{ $purchase->id }}</span></h2>
        <br>
        <p class="page-subtitle">[Hanya perbarui jika diperlukan]</p>
    </div>

    {{-- KOREKSI AMAN: Cek apakah diakses via Customer Area (ada token) atau Admin Area --}}
    @php
        $isCustomerArea = isset($token);
        $updateRoute = $isCustomerArea 
            ? route('company.purchase.edit', ['token' => $token, 'purchase' => $purchase->id]) // Sesuaikan dengan route update tokenmu nanti
            : route('admin.company.dashboard', $purchase->company_id); // Fallback admin atau sesuaikan route update adminmu
            
        $backRoute = $isCustomerArea 
            ? route('company.purchase.show', ['token' => $token, 'purchase' => $purchase->id]) 
            : route('dashboard.show', $purchase->id);

        $imageRoute = $isCustomerArea
            ? route('company.purchase.receipt.image', ['token' => $token, 'purchase' => $purchase->id])
            : route('dashboard.receipt.image', $purchase->id);
    @endphp

    <form method="POST" action="{{ $updateRoute }}" class="modern-form">
        @csrf
        @method('PUT') {{-- Umumnya update data menggunakan method PUT/PATCH --}}

        <div class="form-section">
            <div class="form-grid">
                <div class="form-group">
                    <label for="invoice_date">Tanggal Invoice</label>
                    <input type="date"
                           id="invoice_date"
                           name="invoice_date"
                           class="form-control"
                           value="{{ old('invoice_date', optional($purchase->invoice_date)->format('Y-m-d')) }}">
                </div>

                <div class="form-group">
                    <label for="supplier">Supplier</label>
                    <input type="text"
                           id="supplier"
                           name="supplier"
                           class="form-control"
                           value="{{ old('supplier', $purchase->supplier?->name) }}">
                </div>
            </div>
        </div>
            
        {{-- Panel Nota Asli dengan Kontrol di Dalam Komponen Gambar --}}
        <div class="receipt-panel">
            <button type="button" class="receipt-toggle" onclick="toggleReceipt(); return false;">
                <span>
                    @if($purchase->has_warning)
                        ⚠️ Lihat Nota Asli
                    @else
                        📑 Nota Asli
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
                        <img id="receiptImage" src="{{ $imageRoute }}" class="receipt-image" alt="Nota Asli" draggable="false">
                    </div>

                </div>
            </div>
        </div>

        <div class="form-section">
            <h3 class="section-title">Detail Item</h3>
            
            <div class="table-responsive-wrapper">
                <table class="edit-table">
                    <thead>
                        <tr>
                            <th width="140">Nama Item</th>
                            <th width="60">Qty</th>
                            <th width="90">Harga (Rp)</th>
                            <th width="80" style="text-align: right;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($purchase->details as $i => $detail)
                        <tr>
                            <td data-label="Nama Item">
                                <input type="hidden"
                                       name="items[{{ $i }}][id]"
                                       value="{{ $detail->id }}">
                                <input type="text"
                                       name="items[{{ $i }}][name]"
                                       class="form-control table-input"
                                       value="{{ old('items.'.$i.'.name', $detail->item?->name) }}">
                            </td>

                            <td data-label="Qty">
                                <input type="number"
                                       name="items[{{ $i }}][qty]"
                                       class="form-control table-input text-center"
                                       value="{{ old('items.'.$i.'.qty', (int) $detail->qty) }}"
                                       min="0">
                            </td>

                            <td data-label="Harga">
                                <input type="number"
                                       name="items[{{ $i }}][price]"
                                       class="form-control table-input"
                                       value="{{ old('items.'.$i.'.price', (int) $detail->unit_price) }}"
                                       min="0"
                                       step="1">
                            </td>

                            <td data-label="Subtotal">
                                <input type="number"
                                       name="items[{{ $i }}][subtotal]"
                                       class="form-control table-input"
                                       value="{{ old('items.'.$i.'.subtotal', (int)$detail->total_price) }}"
                                       min="0"
                                       step="1">
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="form-actions">
            <a style="background-color: #ccffcc;" href="{{ $backRoute }}" class="btn-action btn-cancel">
                Kembali
            </a>
            <button type="submit" class="btn-action btn-submit">
                <span>💾 </span> SIMPAN
            </button>
        </div>
    </form>
</div>

<style>
    /* Scope Wrapper */
    .edit-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 15px;
    }

    .highlight {
        color: var(--primary);
    }

    .modern-form {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .form-section {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
    }

    .section-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--secondary);
        margin-bottom: 12px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--bg-global);
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    /* Grid Layout */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group label {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-main);
    }

    /* Form Controls */
    .form-control {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        font-family: var(--font-sans);
        font-size: 14px;
        color: var(--text-main);
        background-color: #fff9dc;
        transition: all 0.2s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
    }

    /* Table Specifics */
    .table-responsive-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .edit-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    .edit-table th {
        background: #f8fafc;
        padding: 12px 14px;
        color: var(--text-muted);
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        border-bottom: 1px solid var(--border-color);
    }

    .edit-table td {
        padding: 12px 6px;
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
    }

    .edit-table tr:last-child td {
        border-bottom: none;
    }

    .table-input {
        padding: 8px 12px;
    }

    .text-center { text-align: center; }

    /* Form Actions */
    .form-actions {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 14px 28px;
        border-radius: var(--radius-md);
        font-family: var(--font-sans);
        font-size: 16px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
        margin: 3px 0;
    }

    .btn-submit {
        background-color: var(--primary);
        color: #fff;
    }

    .btn-submit:hover {
        background-color: var(--primary-hover);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
    }

    .btn-cancel {
        background-color: #ffffffb0;
        color: #000000;
        border: 1px solid #cccccc;
    }

    .btn-cancel:hover {
        background-color: #f1f5f9;
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

    .receipt-container-viewport {
        position: relative;
        width: 100%;
        height: 380px;
        overflow: hidden;
        background: #e2e8f0;
    }

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

    .receipt-image {
        max-width: 100%;
        object-fit: contain;
        transition: transform 0.1s cubic-bezier(0.2, 0, 0, 1);
        transform-origin: center center;
    }

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
        background: #4f46e5;
        color: white;
        font-size: 18px;
        cursor: pointer;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    #zoomLevel {
        min-width: 48px;
        text-align: center;
        font-weight: 700;
        font-size: 13px;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: repeat(2, 1fr); 
            gap: 10px;
        }

        .table-responsive-wrapper {
            margin-bottom:15px;
        }

        .edit-table {
            min-width: 480px;
        }

        .form-actions {
            flex-direction: column-reverse;
        }

        .btn-action {
            width: 100%;
            justify-content: center;
        }
        
        .receipt-container-viewport { height: 300px; }
    }
</style>

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

        // Jalankan refresh awal agar default scale 1.5 langsung bekerja
        refresh();

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