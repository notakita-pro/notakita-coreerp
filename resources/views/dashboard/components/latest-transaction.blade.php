@if(isset($purchases))

@php
    $isCustomer = isset($company);

    $token = $company->access_token ?? null;
@endphp

<div class="latest-section">

    <div class="section-header">

        <div class="section-title">
            Transaksi Terbaru
        </div>

        @if($isCustomer)

            <a
                href="{{ route('company.transaction', $token) }}"
                class="see-all">
                Selengkapnya →
            </a>

        @else

            <a
                href="{{ route('admin.company.transaction', $purchase->company_id ?? 0) }}"
                class="see-all">
                Selengkapnya →
            </a>

        @endif

    </div>

    @forelse($purchases->take(2) as $purchase)

        @php

            $detailUrl = $isCustomer

                ? route(
                    'company.purchase.show',
                    [
                        'token'    => $token,
                        'purchase' => $purchase,
                    ]
                )

                : route(
                    'admin.company.purchase.show',
                    [
                        'company'  => $purchase->company,
                        'purchase' => $purchase,
                    ]
                );

        @endphp

        <div class="latest-card">

            <div class="latest-top">

                <div>

                    <div class="latest-supplier">
                        {{ $purchase->supplier?->name }}
                    </div>

                    <div class="latest-date">
                        {{ optional($purchase->invoice_date)->format('d M Y') }}
                    </div>

                </div>

                <div class="latest-total">
                    Rp {{ number_format($purchase->total,0,',','.') }}
                </div>

            </div>

            <div class="latest-bottom">

                <span class="latest-badge">
                    {{ $purchase->details->count() }} Item
                </span>

                <a
                    href="{{ $detailUrl }}"
                    class="latest-button">

                    Detail

                </a>

            </div>

        </div>

    @empty

        <div class="empty-card">

            Belum ada transaksi.

        </div>

    @endforelse

</div>

<style>
.latest-section {
    margin-bottom: 24px;
}
.latest-section .section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
}
.latest-section .section-title {
    font-size: 18px;
    font-weight: 700;
    color: #0f172a;
}
.see-all {
    text-decoration: none;
    color: #4f46e5;
    font-size: 14px;
    font-weight: 600;
}
.latest-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    padding: 18px;
    margin-bottom: 14px;
    box-shadow: 0 8px 24px rgba(15,23,42,.05);
    transition: .25s ease;
}
.latest-card:hover {
    transform: translateY(-3px);
    border-color: #c7d2fe;
    box-shadow: 0 14px 32px rgba(79,70,229,.12);
}
.latest-top {
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:20px;
    margin-bottom:18px;
}
.latest-supplier {
    font-size:17px;
    font-weight:700;
    color:#0f172a;
}
.latest-date {
    margin-top:4px;
    color:#64748b;
    font-size:13px;
}
.latest-total {
    font-size:18px;
    font-weight:800;
    color:#2563eb;
    text-align:right;
}
.latest-bottom {
    display:flex;
    justify-content:space-between;
    align-items:center;
}
.latest-badge {
    background:#eef2ff;
    color:#4338ca;
    border-radius:30px;
    padding:6px 14px;
    font-size:12px;
    font-weight:700;
}
.latest-button {
    background:#4f46e5;
    color:#fff;
    text-decoration:none;
    padding:9px 18px;
    border-radius:12px;
    font-size:13px;
    font-weight:600;
    transition:.2s;
}
.latest-button:hover {
    background:#4338ca;
}
.empty-card {
    background:#fff;
    border:2px dashed #cbd5e1;
    border-radius:18px;
    padding:28px;
    text-align:center;
    color:#64748b;
}
@media(max-width:768px){
    .latest-card{
        padding:16px;
    }
    .latest-top{
        flex-direction:column;
        gap:10px;
        margin-bottom:14px;
    }
    .latest-total{
        text-align:left;
        font-size:20px;
    }
    .latest-bottom{
        margin-top:8px;
    }
    .latest-button{
        padding:8px 16px;
    }
}
</style>

@endif