@extends('layouts.app')

@section('title', 'Daftar Perusahaan')

@section('content')

<div class="container py-4">

    <div class="page-header">

        <div>
            <h2>Perusahaan</h2>
            <p>
                Pilih perusahaan untuk membuka Dashboard Customer sebagai Administrator.
            </p>
        </div>

        <div class="company-count">
            {{ $companies->total() }} Perusahaan
        </div>

    </div>

    @forelse($companies as $company)

        <div class="company-card">

            <div class="company-left">

                <div class="company-avatar">
                    🏢
                </div>

                <div>

                    <div class="company-name">
                        {{ $company->name }}
                    </div>

                    <div class="company-token">
                        Token :
                        {{ $company->access_token }}
                    </div>

                </div>

            </div>

            <div class="company-right">

                @if(Route::has('admin.company.dashboard'))

                    <a
                        href="{{ route('admin.company.dashboard', $company) }}"
                        class="btn-dashboard"
                    >
                        Dashboard →
                    </a>

                @else

                    <button
                        class="btn-dashboard"
                        disabled
                        title="Route belum tersedia"
                    >
                        Dashboard →
                    </button>

                @endif

            </div>

        </div>

    @empty

        <div class="empty-card">
            Belum ada perusahaan.
        </div>

    @endforelse

    <div class="mt-4">
        {{ $companies->links() }}
    </div>

</div>

@endsection


@push('styles')
<style>

.page-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:28px;
    gap:20px;
    flex-wrap:wrap;
}

.page-header h2{
    margin:0;
    font-size:30px;
    font-weight:700;
}

.page-header p{
    margin-top:6px;
    color:#64748b;
}

.company-count{
    padding:10px 18px;
    background:#eef2ff;
    border-radius:30px;
    font-weight:700;
    color:#4338ca;
}

.company-card{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:22px;
    background:#fff;
    border:1px solid #e2e8f0;
    border-radius:18px;
    margin-bottom:18px;
    transition:.25s;
}

.company-card:hover{
    transform:translateY(-2px);
    box-shadow:0 10px 25px rgba(15,23,42,.08);
}

.company-left{
    display:flex;
    align-items:center;
    gap:18px;
}

.company-avatar{
    width:60px;
    height:60px;
    border-radius:16px;
    background:#eef2ff;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:28px;
}

.company-name{
    font-size:18px;
    font-weight:700;
    margin-bottom:4px;
}

.company-token{
    font-size:13px;
    color:#64748b;
    font-family:monospace;
}

.btn-dashboard{
    background:#2563eb;
    color:#fff;
    text-decoration:none;
    padding:10px 18px;
    border-radius:12px;
    font-weight:600;
    transition:.2s;
    border:none;
    cursor:pointer;
}

.btn-dashboard:hover:not(:disabled){
    background:#1d4ed8;
}

.btn-dashboard:disabled{
    background:#94a3b8;
    cursor:not-allowed;
}

.empty-card{
    background:#fff;
    border:2px dashed #cbd5e1;
    padding:40px;
    text-align:center;
    border-radius:18px;
    color:#64748b;
}

@media(max-width:768px){

    .company-card{
        flex-direction:column;
        align-items:flex-start;
        gap:18px;
    }

    .company-right{
        width:100%;
    }

    .btn-dashboard{
        display:block;
        width:100%;
        text-align:center;
    }

}

</style>
@endpush