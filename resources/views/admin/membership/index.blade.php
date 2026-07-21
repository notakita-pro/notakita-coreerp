@extends('layouts.app')
@section('content')
<div class="container py-4">

    {{-- ==========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="page-header d-flex justify-content-between align-items-center gap-3 mb-4 flex-wrap">
        <div>
            <h2 class="fw-bold mb-1">Membership Center</h2>
            <p class="text-muted m-0">Monitoring seluruh membership perusahaan pada platform CoreERP.</p>
        </div>
        <div>
            <a href="{{ route('admin.dashboard') }}" class="button btn-secondary">
                ← Admin Center
            </a>
        </div>
    </div>

    {{-- ==========================================================
        ALERT
    ========================================================== --}}
    @if(session('success'))
        <div class="alert-success mb-4 fw-semibold p-3 rounded-3">
            ✅ {{ session('success') }}
        </div>
    @endif

    {{-- ==========================================================
        SUMMARY
    ========================================================== --}}
    @php
        $cardStyles = [
            'total'   => '',
            'free'    => 'text-free',
            'silver'  => 'text-silver',
            'gold'    => 'text-gold',
            'active'  => 'text-success',
            'expired' => 'text-danger',
        ];

        $cardLabels = [
            'total'   => 'Total Company',
            'free'    => 'Free',
            'silver'  => 'Silver',
            'gold'    => 'Gold',
            'active'  => 'Active',
            'expired' => 'Expired',
        ];
    @endphp

    <div class="summary-grid mb-4">
        @foreach($cardLabels as $key => $label)
            <div class="summary-card p-4 text-center bg-white border rounded-3">
                <span class="summary-number d-block fs-3 fw-bold mb-1 {{ $cardStyles[$key] }}">
                    {{ $summary[$key] }}
                </span>
                <span class="summary-title text-muted fs-7">
                    {{ $label }}
                </span>
            </div>
        @endforeach
    </div>

    {{-- ==========================================================
        MAINTENANCE CENTER
    ========================================================== --}}
    <div class="maintenance-card bg-white border rounded-3 p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div>
                <h3 class="fs-5 fw-bold m-0">Membership Maintenance</h3>
                <p class="text-muted fs-7 m-0 mt-1">Tool administrasi untuk maintenance Membership Platform.</p>
            </div>
            <span class="badge badge-danger">DEVELOPMENT ONLY</span>
        </div>

        @php
            $maintenanceActions = [
                [
                    'route'   => 'admin.membership.resetExpired',
                    'class'   => 'btn-warning',
                    'icon'    => '🔄',
                    'text'    => 'Reset Membership Expired',
                    'confirm' => 'Reset seluruh Membership EXPIRED menjadi FREE?',
                ],
                [
                    'route'   => 'admin.membership.deleteExpiredInvoices',
                    'class'   => 'btn-danger',
                    'icon'    => '🗑',
                    'text'    => 'Bersihkan Invoice Expired',
                    'confirm' => 'Hapus seluruh Invoice EXPIRED?',
                ],
                [
                    'route'   => 'admin.membership.deleteCancelledInvoices',
                    'class'   => 'btn-dark',
                    'icon'    => '🧹',
                    'text'    => 'Bersihkan Invoice Cancelled',
                    'confirm' => 'Hapus seluruh Invoice CANCELLED?',
                ],
            ];
        @endphp

        <div class="maintenance-grid">
            @foreach($maintenanceActions as $action)
                <form action="{{ route($action['route']) }}" method="POST" onsubmit="return confirm('{{ $action['confirm'] }}')">
                    @csrf
                    <button class="button {{ $action['class'] }} w-100">
                        {{ $action['icon'] }} {{ $action['text'] }}
                    </button>
                </form>
            @endforeach
        </div>

        <hr class="my-4">

        <form action="{{ route('admin.membership.factoryReset') }}" method="POST" onsubmit="return confirm('FACTORY RESET akan menghapus seluruh invoice, payment dan mengembalikan semua company menjadi FREE. Lanjutkan?')">
            @csrf
            <button class="button btn-factory w-100">
                ⚠ FACTORY RESET PLATFORM
            </button>
        </form>
    </div>

    {{-- ==========================================================
        MEMBERSHIP TABLE
    ========================================================== --}}
    <div class="card border rounded-3 bg-white overflow-hidden">
        <div class="table-responsive">
            <table class="table m-0">
                <thead>
                    <tr>
                        <th width="60" style="padding: 15px; text-align: left;">#</th>
                        <th style="padding: 15px; text-align: left;">Company</th>
                        <th width="160" style="padding: 15px; text-align: left;">Membership</th>
                        <th width="170" style="padding: 15px; text-align: left;">Expired</th>
                        <th width="130" style="padding: 15px; text-align: left;">Status</th>
                        <th width="170" style="padding: 15px; text-align: left;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($companies as $company)
                        @php

    $type = strtolower($company->membership_type ?? 'free');

    $expires = $company->membership_expires_at;

    if ($expires && !($expires instanceof \Carbon\Carbon)) {
        $expires = \Carbon\Carbon::parse($expires);
    }

    /*
    |--------------------------------------------------------------------------
    | Membership Status
    |--------------------------------------------------------------------------
    */

    if ($type === 'free') {

        $status = 'FREE';
        $statusClass = 'status-free';

    } elseif ($expires && $expires->isFuture()) {

        $status = 'ACTIVE';
        $statusClass = 'status-active';

    } else {

        $status = 'EXPIRED';
        $statusClass = 'status-expired';

    }

@endphp
                        <tr>
                            <td style="padding: 15px;">{{ $loop->iteration + (($companies->currentPage()-1) * $companies->perPage()) }}</td>
                            <td style="padding: 15px;">
                                <strong class="text-dark">{{ $company->name }}</strong>
                                <br>
                                <small class="text-muted">{{ $company->owner ?? '-' }}</small>
                            </td>
                            <td style="padding: 15px;">
                                <span class="badge badge-{{ $type }}">
                                    {{ strtoupper($type) }}
                                </span>
                            </td>
                            <td style="padding: 15px;">{{ $expires ? $expires->format('d M Y') : '-' }}</td>
                            <td style="padding:15px;">

    <span class="status {{ $statusClass }}">
        {{ $status }}
    </span>

</td>
                            <td style="padding: 15px;">
                                <a href="{{ route('admin.company.dashboard', $company) }}" class="button btn-sm">
                                    Open Dashboard
                                </a>
                            </td>
                        </tr>
                    @empty
                        <td
    colspan="6"
    class="text-center text-muted"
    style="padding:15px;">
    Belum ada data perusahaan.
</td>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination links --}}
    <div class="mt-4">
        {{ $companies->links() }}
    </div>
</div>
@endsection

<style>
.summary-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(170px,1fr));
    gap:16px;
}

.maintenance-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:14px;
}

.summary-card,
.maintenance-card{
    transition:.2s ease;
}

.summary-card:hover,
.maintenance-card:hover{
    transform:translateY(-2px);
}

.alert-success{
    background:#dcfce7;
    color:#166534;
    border:1px solid #86efac;
}

.text-free{ color:#64748b; }
.text-silver{ color:#475569; }
.text-gold{ color:#d97706; }

.badge{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:5px 12px;
    border-radius:999px;
    font-size:12px;
    font-weight:700;
}

.status{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:6px 12px;
    border-radius:999px;
    font-size:12px;
    font-weight:700;
}

.status-free{
    background:#e2e8f0;
    color:#475569;
}

.status-active{
    background:#dcfce7;
    color:#15803d;
}

.status-expired{
    background:#fee2e2;
    color:#b91c1c;
}

.badge-free{ background:#e5e7eb; color:#475569; }
.badge-silver{ background:#e2e8f0; color:#334155; }
.badge-gold{ background:#fef3c7; color:#b45309; }
.badge-danger{ background:#fee2e2; color:#b91c1c; }

.status{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:5px 12px;
    border-radius:999px;
    font-size:12px;
    font-weight:700;
}

.status-active{ background:#dcfce7; color:#15803d; }
.status-expired{ background:#fee2e2; color:#b91c1c; }
.fs-7{ font-size:.86rem; }
.text-muted { color: #64748b; }

.button{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    border:none;
    border-radius:10px;
    padding:10px 16px;
    font-size:13px;
    font-weight:600;
    text-decoration:none;
    transition:.2s;
    cursor:pointer;
}

.button:hover{
    transform:translateY(-1px);
}

.btn-secondary{ background:#eef2ff; color:#2563eb; }
.btn-warning{ background:#f59e0b; color:#fff; }
.btn-danger{ background:#dc2626; color:#fff; }
.btn-dark{ background:#334155; color:#fff; }

.btn-danger:hover{ background:#b91c1c; }
.btn-warning:hover{ background:#d97706; }
.btn-dark:hover{ background:#1e293b; }

.btn-factory{
    background:#7c2d12;
    color:#fff;
    font-weight:700;
}

.btn-factory:hover{
    background:#5b1d0d;
}

.w-100{ width:100%; }
.mt-4 { margin-top: 1.5rem; }
.card { background: #fff; }

.table{
    width:100%;
    border-collapse:collapse;
}

.table th{
    background:#f8fafc;
    white-space:nowrap;
    border-bottom: 1px solid #e2e8f0;
}

.table td{
    vertical-align:middle;
    border-bottom: 1px solid #f1f5f9;
}

.table tbody tr:hover{
    background:#fafcff;
}

@media(max-width:768px){
    .summary-grid{
        grid-template-columns:repeat(2,1fr);
    }
    .maintenance-grid{
        grid-template-columns:1fr;
    }
    .button{
        width:100%;
    }
}
</style>