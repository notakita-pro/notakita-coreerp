{{-- ==========================================================
    PURCHASE TABLE
========================================================== --}}
<div class="erp-table-container">
    <div class="erp-table-wrapper">
        <table class="erp-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tanggal</th>

                    @if(empty($company))
                        <th>Client</th>
                        <th>Dashboard</th>
                    @endif

                    <th>Supplier</th>
                    <th>Total</th>
                    <th>Item</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

            @forelse($purchases as $purchase)

                @php
                    $warning = count($purchase->validation['warnings'] ?? []);
                    $isCustomer = !empty($company);
                @endphp

                <tr>

                    <td>
                        <span class="badge badge-purple">
                            #{{ $purchase->id }}
                        </span>
                    </td>

                    <td>
                        {{ optional($purchase->invoice_date)->format('d-m-Y') }}
                    </td>

                    @if(empty($company))

                        <td>
                            <strong>
                                {{ $purchase->company?->name ?? '--' }}
                            </strong>

                            <br>

                            <small class="text-muted">
                                {{ $purchase->company?->phone ?? '-' }}
                            </small>
                        </td>

                        <td>

                            <a
                                href="{{ route('admin.company.dashboard', $purchase->company) }}"
                                class="button btn-brown">

                                Lihat

                            </a>

                        </td>

                    @endif

                    <td class="supplier-cell">

                        {{ $purchase->supplier?->name ?? 'Supplier Tidak Diketahui' }}

                    </td>

                    <td>

                        <span class="total-amount fw-bold {{ $warning ? 'text-danger' : '' }}">

                            Rp {{ number_format($purchase->total,0,',','.') }}

                        </span>

                        @if($warning)

                            <br>

                            <small class="text-danger">

                                {{ $warning }} masalah

                            </small>

                        @endif

                    </td>

                    <td>

                        <span class="badge badge-gray">

                            {{ $purchase->details?->count() ?? 0 }}

                        </span>

                    </td>

                    <td>

                        <div class="action-cell-wrapper">

                            @if($warning)
                                <span class="badge badge-warning">
                                    Perlu Dicek
                                </span>
                            @else
                                <span class="badge badge-success">
                                    Valid
                                </span>
                            @endif

                            <small class="text-muted">
                                {{ $purchase->created_at->diffForHumans() }}
                            </small>

                            @if($isCustomer)

                                <a
                                    href="{{ route('company.purchase.show', [
                                        'token' => $company->access_token,
                                        'purchase' => $purchase
                                    ]) }}"
                                    class="button btn-green">

                                    Lihat Nota

                                </a>

                            @else

                                <a
                                    href="{{ route('admin.company.purchase.show', [
                                        'company' => $purchase->company,
                                        'purchase' => $purchase
                                    ]) }}"
                                    class="button btn-green">

                                    Lihat Nota

                                </a>

                            @endif

                        </div>

                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="{{ empty($company) ? 7 : 5 }}"
                        class="text-center text-muted"
                        style="padding:40px;">

                        Belum ada data pembelian.

                    </td>

                </tr>

            @endforelse

            </tbody>
        </table>
    </div>
</div>

<style>

.erp-table-container{
    width:100%;
    overflow-x:auto;
    -webkit-overflow-scrolling:touch;
    margin-bottom:1rem;
    border:1px solid #e2e8f0;
    border-radius:8px;
}

.erp-table-wrapper{
    min-width:800px;
    width:100%;
}

.erp-table{
    width:100%;
    border-collapse:collapse;
    display:table!important;
}

.erp-table thead{
    display:table-header-group!important;
}

.erp-table tbody{
    display:table-row-group!important;
}

.erp-table tr{
    display:table-row!important;
}

.erp-table th,
.erp-table td{
    display:table-cell!important;
    padding:12px 16px;
    text-align:left;
    vertical-align:middle;
    border-bottom:1px solid #e2e8f0;
    white-space:nowrap;
}

.erp-table td.supplier-cell,
.erp-table td .action-cell-wrapper{
    white-space:normal;
    min-width:150px;
}

.action-cell-wrapper{
    display:flex;
    align-items:center;
    gap:8px;
    flex-wrap:wrap;
}

</style>