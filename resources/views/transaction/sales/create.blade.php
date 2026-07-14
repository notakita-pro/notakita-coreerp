@extends('layouts.app')

@section('content')
@php
    $token = request()->route('token');
    $isCustomer = !empty($token);

    $storeRoute = $isCustomer ? route('company.sales.store', ['token' => $token]) : route('admin.company.sales.store', $company);
    $backRoute = $isCustomer ? route('company.sales.index', ['token' => $token]) : route('admin.company.sales.index', $company);
@endphp

<div class="container">
    <div class="page-header">
        <div>
            <h2 class="page-title">Invoice Penjualan Baru</h2>
        </div>
    </div>

    <form method="POST" action="{{ $storeRoute }}" id="salesForm">
        @csrf

        {{-- ========================================================== INFORMASI INVOICE ========================================================== --}}
        <div class="card">
            <div class="grid-fixed-2">
                <div>
                    <label>Tgl Invoice</label>
                    <input type="date" name="invoice_date" value="{{ old('invoice_date', date('Y-m-d')) }}" required>
                    
                </div>
                <div>
                    <label>Nomor</label>
                    <input type="text" value="Urut Otomatis" class="form-disabled-brown">
                </div>
            </div>
            <hr class="page-divider">

            <div class="form-group" style="margin-top:20px;">
                <label>Pelanggan (Pembeli)</label>
                <div style="display:flex; gap:10px;">
                    <input 
                        type="text" 
                        id="customerDisplay" 
                        value="{{ old('new_customer_name', $selectedCustomer ? $selectedCustomer->name . ($selectedCustomer->address ? ' - ' . $selectedCustomer->address : '') : 'Customer Umum') }}" 
                        class="form-disabled-brown">
                    <button type="button" id="btnCreateSale" class="button btn-green">Edit</button>
                </div>
                <input 
                    type="hidden" 
                    id="customer_id" 
                    name="customer_id" 
                    value="{{ old('customer_id', $selectedCustomer?->id) }}">
            </div>
        </div>
{{-- ========================================================== PEMBAYARAN ========================================================== --}}
<div class="card mt-4">
    <div class="grid-fixed-2">
        <div class="form-group">
            <label>Sistem Bayar</label>
            <select name="payment_term" id="paymentTerm" class="form-disabled-brown">
                <option value="cash" @selected(old('payment_term', 'cash') == 'cash')>Tunai</option>
                <option value="credit" @selected(old('payment_term') == 'credit')>Tempo</option>
            </select>
        </div>
        <div class="form-group" id="groupMainDownPayment">
            <label>DP (Uang Muka)</label>
            <input type="number" name="down_payment" id="downPayment" min="0" value="{{ old('down_payment', 0) }}" class="form-disabled-brown">
        </div>
        <div class="form-group" id="groupMainDueDate">
            <label>Jatuh Tempo</label>
            <input type="date" name="due_date" value="{{ old('due_date') }}" class="form-disabled-brown">
        </div>
        <div class="form-group">
            <label>Metode Bayar</label>
            <select name="payment_method" id="paymentMethod" class="form-disabled-brown">
                <option value="cash">Uang Cash</option>
                <option value="transfer">Transfer</option>
                <option value="unpaid">Belum Bayar</option>
            </select>
        </div>
    </div>
    {{-- Tombol EDIT tetap stand-by --}}
    <div style="margin-top: 15px; text-align: right;">
        <button type="button" id="btnEditPayment" class="button btn-green">Edit</button>
    </div>
    </div>
        {{-- ========================================================== DETAIL PRODUK ========================================================== --}}
        <div class="card mt-4">
            <h4>- Detail Produk</h4>
            <div class="table-responsive">
                <table class="sales-table" id="salesTable">
                    <thead>
                        <tr>
                            <th width="25%">Nama Barang</th>
                            <th width="5%">Qty</th>
                            <th width="7%">Sat.</th>
                            <th width="12%">Harga</th>
                            <th width="15%">Jumlah</th>

                        </tr>
                    </thead>
                    <tbody id="tbodySales">
                        <tr>
                            <td>
                                <input type="hidden" name="items[0][item_id]">
                                <input type="text" name="items[0][item_name]" placeholder="Nama barang / jasa" class="form-disabled-brown" required >
                            </td>
                            <td><input type="number" min="0" step="0.01" value="1" class="form-disabled-brown" name="items[0][qty]"></td>
                            <td><input type="text" name="items[0][unit]" placeholder="pcs" class="form-disabled-brown"></td>
                            <td><input type="number" min="0" step="0.01" value="0" class="form-disabled-brown" name="items[0][unit_price]"></td>
                            <td><input type="text" class="form-disabled-brown" value="Rp 0" readonly ></td>

                        </tr>
                    </tbody>
                </table>
            </div>
            <div style="margin-top:18px; text-align:right;">
        <button type="button" id="btnEditItems" class="button btn-green">Edit Daftar Barang</button>
    </div>
        </div>

        {{-- ========================================================== RINGKASAN INVOICE ========================================================== --}}
        <div class="card mt-4">
            <h4>Ringkasan Invoice</h4>
            <div class="summary-box">
                <div>
                    <label>Subtotal</label>
                    <input type="text" id="subtotalAll" value="Rp 0" class="form-disabled-brown">
                </div>
                <div>
                    <label>Diskon Invoice</label>
                    <input type="number" id="discount" name="discount" min="0" step="0.01" value="{{ old('discount', 0) }}">
                </div>
                <div>
                    <label>Pajak Invoice</label>
                    <input type="number" id="tax" name="tax" min="0" step="0.01" value="{{ old('tax', 0) }}">
                </div>
                <div>
                    <label>Transport (Ongkir)</label>
                    <input type="number" id="transport" name="transport" min="0" step="0.01" value="{{ old('transport', 0) }}">
                </div>
                <div>
                    <label>Biaya Lainnya</label>
                    <input type="number" id="other_cost" name="other_cost" min="0" step="0.01" value="{{ old('other_cost', 0) }}">
                </div>
                <div>
                    <label>Grand Total</label>
                    <input type="text" id="grandTotal" value="Rp 0" class="form-disabled-brown">
                    <input type="hidden" id="grandTotalHidden" name="grand_total">
                </div>
            </div>
        </div>

        {{-- ========================================================== CATATAN & SUBMIT ========================================================== --}}
        <div class="card mt-4">
            <div class="form-group">
                <label>Catatan</label>
                <textarea name="notes" rows="4" placeholder="Catatan transaksi...">{{ old('notes') }}</textarea>
            </div>
            <div class="button-group">
                <button type="submit" class="button btn-green">💾 Simpan Penjualan</button>
                <a href="{{ $backRoute }}" class="button btn-blue">← Kembali</a>
            </div>
        </div>

        {{-- Input Hidden Data Customer Baru --}}
        <input type="hidden" id="newCustomerName" name="new_customer_name" value="{{ old('new_customer_name') }}">
        <input type="hidden" id="newCustomerAddress" name="new_customer_address" value="{{ old('new_customer_address') }}">
        <input type="hidden" id="newCustomerPhone" name="new_customer_phone" value="{{ old('new_customer_phone') }}">
        
        {{-- KUNCI PENYEMBUHAN BARU: Tambahkan ini untuk mengirimkan nilai uang yang dibayarkan ke backend --}}
        <input type="hidden" id="amountPaidHidden" name="amount_paid" value="{{ old('amount_paid', 0) }}">
    </form>
</div>

{{-- Include komponen modal diletakkan rapi di sini --}}
@include('transaction.sales.components.customer-modal')
@include('transaction.sales.components.payment-modal')
@include('transaction.sales.components.sales-table-modal')

@endsection


<style>

    .page-divider { margin: 15px 0; }
    .form-group { margin-top: 15px; }
    
    /* Grid responsive bawaan */
    .grid-3, .grid-2 { 
        display: grid; 
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); 
        gap: 18px; 
    }
    
    /* Grid khusus yang dikunci 2 kolom untuk tampilan Mobile Pembayaran */
    .grid-fixed-2 {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px 18px;
    }

    .table-responsive { width: 100%; overflow-x: auto; }
    .sales-table { width: 100%; border-collapse: collapse; min-width: 550px; }
    .sales-table th { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
    .sales-table td { padding: 6px; }
    .sales-table input, 
    .sales-table select { 
        width: 100%; 
        padding: 6px; 
        background: #ffffe5; 
        border: solid .5px #c9c9c9; 
        border-radius: 4px; 
    }

    /* Tombol hapus baris barang */
    .btn-delete { width: 38px; height: 38px; border: none; border-radius: 8px; cursor: pointer; background: #ffe5e5; }
    .btn-delete:hover { background: #ffbfbf; }


    .summary-box { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; }
    .summary-box input { width: 100%; padding: 8px; border: 1px solid #b4b4b4; border-radius: 5px; background-color: #fcffda;; }
    .button-group { display: flex; gap: 10px; margin-top: 25px; flex-wrap: wrap; }
    input {
    background: #fcffda;
    border: 1px solid #929292;
    padding: 5px;
    border-radius: 4px; }

    .form-disabled-brown {
        color: brown !important;
        background-color: #fcffda !important; /* Warna kuning soft agar serasi dengan input tabel */
        border: solid .5px #c9c9c9;
        border-radius: 4px;
        width: 100%;
        padding: 8px;
    }
    
    /* Sembunyikan panah dropdown khusus IE / Edge lama */
    .form-disabled-brown::-ms-expand { 
        display: none; 
    }

    @media(max-width: 768px) {
        .card { padding: 14px; }
        .container { margin: 15px auto; padding: 0 5px; }
        
        .form-disabled-brown {
        background-color: #f1f1f1 !important; /* Warna kuning soft agar serasi dengan input tabel */
        cursor: not-allowed;
        pointer-events: none; 
        padding: 6px;
        
        /* Proteksi menyembunyikan panah select dropdown */
        -webkit-appearance: none;  
        -moz-appearance: none;     
        appearance: none;  
        }
        .flex-coloumn {
        display: flex;
        gap: 14px;
        }
        input.form-control-modal.modal-item-price {
        width: 170px;
        }
        label{
        color:balck;
        margin-left:8px;
        }
        
        
    }
</style>
@push('scripts')
    <script>
        // Definisikan route saat ini secara dinamis agar bisa dibaca oleh script js
        window.salesCreateRoute = "{{ $isCustomer ? route('company.sales.create', ['token' => $token]) : route('admin.company.sales.create', $company) }}";
    </script>

    {{-- Pemanggilan file JavaScript Komponen --}}
    <script src="{{ asset('js/components/customer-modal.js') }}"></script>
    <script src="{{ asset('js/components/create.js') }}"></script>
    <script src="{{ asset('js/components/items-modal.js') }}"></script>
@endpush
