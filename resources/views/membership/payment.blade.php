@extends('layouts.app')

@section('content')

<div class="payment-container">

    {{-- ================= HEADER ================= --}}
    <div class="payment-header">
        <h1>Invoice Membership</h1>
        <p>Silakan selesaikan pembayaran sebelum invoice berakhir.</p>
    </div>

    {{-- ================= STATUS PEMBAYARAN ================= --}}
    @php
        $statusMap = [
            'PAID' => [
                'class' => 'success',
                'label' => '✅ PEMBAYARAN BERHASIL',
                'badge' => 'Lunas'
            ],
            'PENDING' => [
                'class' => 'pending',
                'label' => '⏳ MENUNGGU PEMBAYARAN',
                'badge' => 'Pending'
            ],
            'EXPIRED' => [
                'class' => 'expired',
                'label' => '❌ INVOICE KEDALUWARSA',
                'badge' => 'Expired'
            ],
            'CANCELLED' => [
                'class' => 'cancelled',
                'label' => '⚠ PEMBAYARAN DIBATALKAN',
                'badge' => 'Cancelled'
            ],
            'FAILED' => [
                'class' => 'cancelled',
                'label' => '⚠ PEMBAYARAN GAGAL',
                'badge' => 'Failed'
            ],
        ];

        $currentStatus = $statusMap[$order->status]
            ?? [
                'class' => 'cancelled',
                'label' => strtoupper($order->status),
                'badge' => strtoupper($order->status),
            ];
    @endphp

    <div class="status-card">
        <div class="status {{ $currentStatus['class'] }}">
            {{ $currentStatus['label'] }}
        </div>
    </div>

    {{-- ================= INFORMASI INVOICE ================= --}}
    <div class="invoice-card">

        <table class="invoice-table">

            <tr>
                <td>Nomor Invoice</td>
                <td>
                    <strong>{{ $order->invoice_number }}</strong>
                </td>
            </tr>

            <tr>
                <td>Perusahaan</td>
                <td>{{ $company->name }}</td>
            </tr>

            <tr>
                <td>Paket Membership</td>
                <td>{{ ucfirst($order->package) }}</td>
            </tr>

            <tr>
                <td>Total Tagihan</td>
                <td class="amount">
                    Rp {{ number_format($order->amount,0,',','.') }}
                </td>
            </tr>

            <tr>
                <td>Status</td>
                <td>
                    <span class="badge {{ $currentStatus['class'] }}">
                        {{ $currentStatus['badge'] }}
                    </span>
                </td>
            </tr>

            <tr>
                <td>Berlaku Sampai</td>
                <td>
                    {{ optional($order->expires_at)->format('d M Y H:i') ?? '-' }}
                </td>
            </tr>

        </table>

    </div>

    {{-- ================= PETUNJUK ================= --}}
    <div class="instruction-card">

        <h3>Petunjuk Pembayaran</h3>

        <ol>
            <li>Klik tombol <strong>Bayar Sekarang</strong>.</li>
            <li>Pilih metode pembayaran.</li>
            <li>Selesaikan pembayaran.</li>
            <li>Status Membership akan aktif otomatis setelah pembayaran berhasil diverifikasi.</li>
        </ol>

    </div>

    {{-- ================= BUTTON ================= --}}
    <div class="payment-action">

        @if($order->status === 'PENDING')

            @if($order->external_id)

                <button
                    id="pay-button"
                    type="button"
                    class="button btn-blue">

                    💳 Bayar Sekarang

                </button>

            @else

                <button
                    class="button btn-secondary"
                    disabled>

                    Menghubungkan ke Midtrans...

                </button>

            @endif

        @elseif($order->status === 'PAID')

            <button
                class="button btn-green"
                disabled>

                ✅ Pembayaran Berhasil

            </button>

        @elseif($order->status === 'EXPIRED')

            <button
                class="button btn-secondary"
                disabled>

                Invoice Kedaluwarsa

            </button>

        @else

            <button
                class="button btn-secondary"
                disabled>

                {{ strtoupper($order->status) }}

            </button>

        @endif

        <a
            href="{{ route('company.membership',$company->access_token) }}"
            class="button btn-light">

            ← Kembali

        </a>

    </div>

</div>

<style>

.payment-container{
    max-width:760px;
    margin:auto;
    font-family:system-ui,-apple-system,sans-serif;
}

.payment-header{
    background:linear-gradient(135deg,#2563eb,#1d4ed8);
    color:white;
    padding:35px;
    border-radius:16px;
    text-align:center;
    margin-bottom:25px;
}

.payment-header h1{
    margin:0 0 10px;
}

.payment-header p{
    margin:0;
}

.status-card{
    margin-bottom:25px;
}

.status{
    padding:18px;
    border-radius:12px;
    font-weight:bold;
    text-align:center;
    font-size:18px;
}

.pending{
    background:#fff8db;
    color:#a16207;
}

.success{
    background:#dcfce7;
    color:#166534;
}

.expired{
    background:#fee2e2;
    color:#991b1b;
}

.cancelled{
    background:#ececec;
    color:#444;
}

.invoice-card,
.instruction-card{
    background:white;
    border-radius:16px;
    padding:30px;
    margin-bottom:25px;
    box-shadow:0 4px 15px rgba(0,0,0,.08);
}

.invoice-table{
    width:100%;
    border-collapse:collapse;
}

.invoice-table td{
    padding:14px;
    border-bottom:1px solid #eee;
}

.invoice-table tr:last-child td{
    border-bottom:none;
}

.amount{
    font-size:28px;
    color:#2563eb;
    font-weight:bold;
}

.badge{
    display:inline-block;
    padding:6px 15px;
    border-radius:20px;
    font-size:13px;
    font-weight:bold;
}

.instruction-card h3{
    margin-top:0;
}

.instruction-card ol{
    padding-left:20px;
}

.instruction-card li{
    margin-bottom:12px;
}

.button{
    display:inline-block;
    padding:12px 24px;
    border:none;
    border-radius:8px;
    text-decoration:none;
    cursor:pointer;
    font-weight:600;
}

.btn-blue{
    background:#2563eb;
    color:white;
}

.btn-green{
    background:#16a34a;
    color:white;
}

.btn-secondary{
    background:#6b7280;
    color:white;
}

.btn-light{
    background:#f3f4f6;
    color:#111827;
    border:1px solid #ddd;
}

.payment-action{
    display:flex;
    gap:15px;
    justify-content:center;
    flex-wrap:wrap;
}

.payment-action .button{
    min-width:220px;
    text-align:center;
}

@media(max-width:768px){

.payment-header{
    padding:24px;
}

.amount{
    font-size:22px;
}

.payment-action{
    flex-direction:column;
}

.payment-action .button{
    width:100%;
}

}

</style>

@if($order->status === 'PENDING' && $order->external_id)

<script
    src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('midtrans.client_key') }}">
</script>

<script>

const paymentUrl = @json(
    route('company.payment',[
        'token'=>$company->access_token,
        'order'=>$order
    ])
);

const payButton = document.getElementById('pay-button');

if(payButton){

    payButton.addEventListener('click',function(){

        snap.pay('{{ $order->external_id }}',{

            onSuccess: function(){
                window.location.href = paymentUrl;
            },

            onPending: function(){
                window.location.href = paymentUrl;
            },

            onError: function(){
                window.location.href = paymentUrl;
            },

            onClose: function(){
                window.location.href = paymentUrl;
            }

        });

    });

}

</script>

@endif

@endsection