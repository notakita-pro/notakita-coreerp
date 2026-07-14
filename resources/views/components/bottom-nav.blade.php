@php
    $token = $token
        ?? ($company->access_token ?? session('company_token'));
@endphp

{{-- Bottom Navigation hanya untuk Customer --}}
@if($token)

<div class="bottom-nav-wrapper">

    <div class="bottom-nav-container">

        {{-- Home --}}
        <a href="{{ route('company.dashboard', ['token' => $token]) }}"
           class="bottom-nav-item {{ request()->routeIs('company.dashboard') ? 'active' : '' }}">
            <div class="bottom-nav-icon">🏠</div>
            <span class="bottom-nav-label">Home</span>
        </a>

        {{-- Transaksi --}}
        <a href="{{ route('company.transaction', ['token' => $token]) }}"
           class="bottom-nav-item {{ request()->routeIs('company.transaction*') ? 'active' : '' }}">
            <div class="bottom-nav-icon">🧾</div>
            <span class="bottom-nav-label">Transaksi</span>
            
            
        </a>

        {{-- Scan --}}
        <div class="bottom-nav-action-wrapper">

            <a href="#"
               class="bottom-nav-action">

                <div class="bottom-nav-icon-action">
                    📷
                </div>

            </a>

        </div>

        {{-- Laporan --}}
       {{-- Laporan --}}
<a href="{{ route('company.report.index', ['token' => $token]) }}"
   class="bottom-nav-item {{ request()->routeIs('company.report.*') ? 'active' : '' }}">

    <div class="bottom-nav-icon">
        📊
    </div>

    <span class="bottom-nav-label">
        Laporan
    </span>

</a>

        {{-- Profil --}}
        <a href="{{ route('company.profile.edit', ['token' => $token]) }}"
           class="bottom-nav-item {{ request()->routeIs('company.profile*') ? 'active' : '' }}">
            <div class="bottom-nav-icon">⚙️</div>
            <span class="bottom-nav-label">Profil</span>
        </a>

    </div>

</div>

@endif


<style>

/* ==========================================================
   BOTTOM NAVIGATION SYSTEM
========================================================== */

.bottom-nav-wrapper{
    position:fixed;
    left:0;
    right:0;
    bottom:0;
    z-index:800;
    background:rgba(255,255,255,.94);
    backdrop-filter:blur(14px);
    -webkit-backdrop-filter:blur(14px);
    border-top:1px solid var(--border-color);
    box-shadow:0 -4px 20px rgba(15,23,42,.04);
    padding-bottom:env(safe-area-inset-bottom);
}

.bottom-nav-container{
    max-width:600px;
    margin:auto;
    display:flex;
    justify-content:space-around;
    align-items:center;
    height:66px;
    padding:0 8px;
}

.bottom-nav-item{
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    flex:1;
    height:100%;
    gap:4px;
    text-decoration:none;
    color:var(--text-muted);
    transition:var(--transition);
}

.bottom-nav-icon{
    font-size:20px;
}

.bottom-nav-label{
    font-size:11px;
    font-weight:600;
    letter-spacing:-.1px;
}

.bottom-nav-item:hover{
    color:var(--primary);
}

.bottom-nav-item.active{
    color:#eab308;
    background:#f3f7ff;
    border-radius:15px;
}

.bottom-nav-item.active .bottom-nav-icon{
    transform:translateY(-2px);
    filter:drop-shadow(0 2px 4px rgba(234,179,8,.2));
}

.bottom-nav-action-wrapper{
    flex:1;
    display:flex;
    justify-content:center;
    align-items:center;
    position:relative;
    height:100%;
}

.bottom-nav-action{
    position:absolute;
    top:-16px;
    width:52px;
    height:52px;
    border-radius:50%;
    background:linear-gradient(135deg,var(--primary),#2563eb);
    display:flex;
    justify-content:center;
    align-items:center;
    text-decoration:none;
    box-shadow:0 4px 14px rgba(79,70,229,.35);
    transition:var(--transition);
}

.bottom-nav-action:hover{
    transform:translateY(-3px) scale(1.05);
}

.bottom-nav-icon-action{
    font-size:18px;
    color:#fff;
}

@media (min-width:769px){

    .bottom-nav-wrapper{
        display:none;
    }

}

</style>