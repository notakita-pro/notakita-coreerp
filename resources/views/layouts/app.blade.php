<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>NOTAKITA INTI SOLUSI</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css','resources/js/app.js'])
    @stack('styles')
</head>

<body>

<header class="main-header">
    <div class="logo">
        {{-- Pembungkus Aman: Mencegah Undefined Variable $company di halaman lain --}}
        @if(!empty($company))
            <div class="company-avatar">
                {{ strtoupper(substr($company->name ?? 'C', 0, 2)) }}
            </div>
            <div class="home-company-detail">
                <div class="page-title">
                    {{ $company->name }}
                </div>
                @if(!empty($company->phone))
                    <div class="home-company-phone text-muted text-phone">
                        {{ $company->phone }}
                    </div>
                @endif
            </div>
        @else
            {{-- Fallback jika diakses dari halaman non-company (misal Login/Guest) --}}
            <div class="company-avatar">ERP</div>
            <div class="home-company-detail">
                <div class="page-title">ERP-OCR</div>
            </div>
        @endif
    </div>
</header>
<style>
header.main-header {
    padding: 10px 15px;
    font-size: 12px;
}
.page-title{
    font-size:19px;
}
.home-company-phone{
    font-size:16px;
}
</style>

<main class="container">
    @if(session('success'))
        <div class="alert alert-success">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            ❌ {{ session('error') }}
        </div>
    @endif

    @yield('content')
</main>

@include('components.bottom-nav')

{{-- Session Lock --}}
<div id="sessionLock" class="session-lock">
    <div class="lock-card">
        <div class="lock-icon">🔒</div>
        <div class="lock-title">Sesi Akan Berakhir</div>
        <div class="lock-text">
            Demi keamanan data perusahaan, sesi akan dikunci secara otomatis.
        </div>
        <div id="lockCounter" class="lock-counter">15</div>
        <button id="stayLoggedIn" class="lock-button">Saya Masih di Sini</button>
    </div>
</div>

{{-- Global Confirm --}}
<div id="confirmModal" class="confirm-modal">
    <div class="confirm-card">
        <div class="confirm-icon">⚠️</div>
        <div class="confirm-title">Konfirmasi</div>
        <div id="confirmMessage" class="confirm-message">Apakah Anda yakin?</div>
        <div class="confirm-buttons">
            <button id="confirmCancel" class="button btn-grey">Batal</button>
            <button id="confirmOk" class="button btn-red">Hapus</button>
        </div>
    </div>
</div>

<form id="autoLogoutForm" method="POST" action="{{ route('company.logout') }}" style="display:none;">
    @csrf
</form>

<form id="globalDeleteForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.alert').forEach(function (alert) {
        setTimeout(function () {
            alert.classList.add('hide');
            setTimeout(function () {
                alert.remove();
            }, 500);
        }, 4000);
    });
});
</script>

@if(session('company_authenticated'))
<script src="{{ asset('js/session-timeout.js') }}"></script>
@endif

<script src="{{ asset('js/confirm.js') }}"></script>
@stack('scripts')

</body>
</html>