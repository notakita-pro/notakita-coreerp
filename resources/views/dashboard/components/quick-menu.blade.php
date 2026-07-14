@if(!empty($company))

<div class="quick-menu-section">

    <div class="section-title">
        Akses Cepat
    </div>

    <div class="quick-menu-grid">

        {{-- 1. Tombol Laporan --}}
        <a href="{{ Route::has('company.report.index') ? route('company.report.index', ['token' => $company->access_token]) : (Route::has('company.reportindex') ? route('company.reportindex', ['token' => $company->access_token]) : '#') }}"
           class="quick-menu-card">
            <div class="quick-icon">📊</div>
            <div class="quick-title">Laporan</div>
        </a>

        {{-- 2. Tombol Upgrade --}}
        <a href="{{ Route::has('company.membership') ? route('company.membership', ['token' => $company->access_token]) : '#' }}"
           class="quick-menu-card">
            <div class="quick-icon">⭐</div>
            <div class="quick-title">Upgrade</div>
        </a>

        {{-- 3. Tombol Utang --}}
        <a href="#" class="quick-menu-card">
            <div class="quick-icon">💳</div>
            <div class="quick-title">Utang</div>
        </a>

        {{-- 4. Tombol Profil --}}
        <a href="{{ Route::has('company.profile') ? route('company.profile', ['token' => $company->access_token]) : (Route::has('company.profile.edit') ? route('company.profile.edit', ['token' => $company->access_token]) : '#') }}"
           class="quick-menu-card">
            <div class="quick-icon">⚙️</div>
            <div class="quick-title">Profil</div>
        </a>

    </div>

</div>

<style>
.quick-menu-section{
    margin-bottom:24px;
}
.quick-menu-section .section-title{
    font-size:18px;
    font-weight:700;
    color:#0f172a;
    margin-bottom:16px;
}
.quick-menu-grid{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:16px;
}
.quick-menu-card{
    background:#ffffff;
    border:1px solid #e2e8f0;
    border-radius:18px;
    padding:18px 12px;
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    text-decoration:none;
    transition:.25s ease;
    box-shadow:0 8px 24px rgba(15,23,42,.05);
}
.quick-menu-card:hover{
    transform:translateY(-4px);
    border-color:#c7d2fe;
    box-shadow:0 16px 32px rgba(79,70,229,.12);
}
.quick-icon{
    width:58px;
    height:58px;
    border-radius:18px;
    display:flex;
    align-items:center;
    justify-content:center;
    background:linear-gradient(135deg,#eef2ff,#dbeafe);
    font-size:28px;
    margin-bottom:12px;
}
.quick-title{
    font-size:14px;
    font-weight:700;
    color:#334155;
    text-align:center;
}
@media(max-width:768px){
    .quick-menu-grid{
        grid-template-columns:repeat(4,1fr);
        gap:10px;
    }
    .quick-menu-card{
        padding:10px 6px;
        border-radius:16px;
    }
    .quick-icon{
        width:48px;
        height:48px;
        border-radius:14px;
        font-size:22px;
        margin-bottom:8px;
    }
    .quick-title{
        font-size:12px;
        line-height:1.3;
    }
}
</style>

@endif
