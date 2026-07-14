@extends('layouts.app')

@section('content')

<div class="transaction-page">

    {{-- ==========================================================
        ACTION BAR
    ========================================================== --}}
    <div class="transaction-action-bar">

        <a href="{{ route('company.report',$company->access_token) }}"
           class="button btn-blue">
            📊 Laporan Pembelian
        </a>

        <a href="{{ route('company.membership',$company->access_token) }}"
           class="button btn-purple">
            📈 Laporan Penjualan
        </a>

    </div>


    {{-- ==========================================================
        HEADER
    ========================================================== --}}
    <div class="transaction-header">

        <div>
            <div class="page-title">
                Daftar Transaksi
            </div>

            <div class="page-subtitle">
                Seluruh pembelian perusahaan yang telah tercatat.
            </div>
        </div>

        <div class="transaction-counter">

            {{ $transactions->total() }}

            <small>Transaksi</small>

        </div>

    </div>


    {{-- ==========================================================
        LIST TRANSACTION
    ========================================================== --}}

    @forelse($transactions as $transaction)

        <div class="transaction-card">

            <div class="transaction-top">

                <div>

                    <div class="transaction-supplier">
                        {{ $transaction->supplier?->name ?? 'Supplier Tidak Diketahui' }}
                    </div>

                    <div class="transaction-date">

                        {{ optional($transaction->invoice_date)->format('d M Y') }}

                        •

                        {{ $transaction->created_at->diffForHumans() }}

                    </div>

                </div>

                <div class="transaction-id">

                    #{{ $transaction->id }}

                </div>

            </div>


            <div class="transaction-middle">

                <div class="transaction-total">

                    Rp {{ number_format($transaction->total,0,',','.') }}

                </div>

            </div>


            <div class="transaction-info-grid">

                <div class="info-box">

                    <div class="info-label">

                        Item

                    </div>

                    <div class="info-value">

                        {{ $transaction->details->count() }}

                    </div>

                </div>


                <div class="info-box">

                    <div class="info-label">

                        Invoice

                    </div>

                    <div class="info-value">

                        {{ $transaction->invoice_number ?? '-' }}

                    </div>

                </div>


                <div class="info-box">

                    <div class="info-label">

                        Status

                    </div>

                    <div class="info-value success">

                        ✔ Tersimpan

                    </div>

                </div>

            </div>


            <div class="transaction-footer">

                <a
                    href="{{ route('dashboard.show',$transaction->id) }}"
                    class="button btn-green">

                    📄 Lihat Nota

                </a>

            </div>

        </div>

    @empty

        <div class="transaction-empty">

            <div class="empty-icon">

                📂

            </div>

            <div class="empty-title">

                Belum ada transaksi.

            </div>

            <div class="empty-description">

                Setelah Anda melakukan scan nota atau input pembelian,
                transaksi akan muncul di halaman ini.

            </div>

        </div>

    @endforelse


    {{-- ==========================================================
        PAGINATION
    ========================================================== --}}

    @if($transactions->hasPages())

        <div class="pagination-wrapper">

            {{ $transactions->links() }}

        </div>

    @endif


    @if(empty($company))

        <form action="{{ route('logout') }}" method="POST">

            @csrf

            <button
                type="submit"
                class="button btn-red">

                Logout

            </button>

        </form>

    @endif

</div>

<style>
.transaction-page{
    display:flex;
    flex-direction:column;
    gap:20px;
    max-width:900px;
    margin:auto;
}

.transaction-action-bar{
    display:flex;
    gap:14px;
    flex-wrap:wrap;
}

.transaction-action-bar .button{
    flex:1;
    min-width:220px;
    text-align:center;
}

.transaction-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:20px;
}

.transaction-counter{
    width:90px;
    height:90px;
    border-radius:22px;
    background:linear-gradient(135deg,var(--primary),#2563eb);
    color:#fff;
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    font-size:28px;
    font-weight:800;
    box-shadow:var(--shadow-md);
    flex-shrink:0;
}

.transaction-counter small{
    font-size:12px;
    font-weight:600;
    opacity:.9;
}

.transaction-card{
    background:#fff;
    border:1px solid var(--border-color);
    border-radius:22px;
    padding:22px;
    box-shadow:var(--shadow-sm);
    transition:.25s;
}

.transaction-card:hover{
    transform:translateY(-3px);
    box-shadow:var(--shadow-md);
}

.transaction-top{
    display:flex;
    justify-content:space-between;
    gap:18px;
    align-items:flex-start;
}

.transaction-supplier{
    font-size:20px;
    font-weight:700;
    color:var(--text-main);
}

.transaction-date{
    margin-top:6px;
    color:var(--text-muted);
    font-size:13px;
}

.transaction-id{
    background:#eef2ff;
    color:var(--primary);
    padding:8px 14px;
    border-radius:999px;
    font-size:13px;
    font-weight:700;
    white-space:nowrap;
}

.transaction-middle{
    margin:24px 0;
}

.transaction-total{
    font-size:34px;
    font-weight:800;
    color:var(--secondary);
    letter-spacing:-1px;
}

.transaction-info-grid{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:14px;
}

.info-box{
    background:#f8fafc;
    border:1px solid var(--border-color);
    border-radius:16px;
    padding:16px;
    text-align:center;
}

.info-label{
    font-size:12px;
    color:var(--text-muted);
    margin-bottom:8px;
}

.info-value{
    font-size:18px;
    font-weight:700;
    color:var(--text-main);
}

.info-value.success{
    color:#16a34a;
}

.transaction-footer{
    margin-top:22px;
    display:flex;
    justify-content:flex-end;
}

.transaction-footer .button{
    min-width:170px;
}

.transaction-empty{
    background:#fff;
    border:2px dashed #cbd5e1;
    border-radius:22px;
    padding:60px 30px;
    text-align:center;
}

.empty-icon{
    font-size:60px;
    margin-bottom:18px;
}

.empty-title{
    font-size:22px;
    font-weight:700;
    margin-bottom:10px;
}

.empty-description{
    color:var(--text-muted);
    max-width:420px;
    margin:auto;
    line-height:1.7;
}

.pagination-wrapper{
    margin-top:10px;
    display:flex;
    justify-content:center;
}

.transaction-card,
.transaction-header,
.transaction-counter{
    animation:fadeTransaction .35s ease;
}

@keyframes fadeTransaction{
    from{
        opacity:0;
        transform:translateY(10px);
    }
    to{
        opacity:1;
        transform:none;
    }
}

@media(max-width:768px){

    .transaction-page{
        gap:16px;
    }

    .transaction-action-bar{
        flex-direction:column;
    }

    .transaction-action-bar .button{
        min-width:unset;
        width:100%;
    }

    .transaction-header{
        flex-direction:column;
        align-items:flex-start;
    }

    .transaction-counter{
        width:100%;
        height:70px;
        border-radius:18px;
        flex-direction:row;
        gap:10px;
        justify-content:center;
        font-size:24px;
    }

    .transaction-counter small{
        font-size:14px;
    }

    .transaction-card{
        padding:18px;
        border-radius:18px;
    }

    .transaction-top{
        flex-direction:column;
        gap:12px;
    }

    .transaction-id{
        align-self:flex-start;
    }

    .transaction-supplier{
        font-size:18px;
    }

    .transaction-total{
        font-size:28px;
    }

    .transaction-info-grid{
        grid-template-columns:1fr;
        gap:10px;
    }

    .transaction-footer{
        justify-content:stretch;
    }

    .transaction-footer .button{
        width:100%;
    }

    .empty-icon{
        font-size:48px;
    }

    .empty-title{
        font-size:20px;
    }
}

</style>

@endsection