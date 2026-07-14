@extends('layouts.app')

@section('content')
<div class="login-wrapper">
    <div class="login-card">
        <h2>Administrator Login</h2>
        <p class="subtitle">
            Silakan masuk untuk mengakses Dashboard Administrator.
        </p>

        @if($errors->any())
            <div class="alert">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- PERBAIKAN: Mengarahkan action POST langsung ke rute name('login') yang memproses autentikasi --}}
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label>Username</label>
                <input
                    type="text"
                    name="username"
                    value="{{ old('username') }}"
                    required
                    autofocus>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input
                    type="password"
                    name="password"
                    required>
            </div>

            <button class="button btn-blue" type="submit">
                Login
            </button>
        </form>
    </div>
</div>

<style>
.login-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 70vh;
}
.login-card {
    width: 100%;
    max-width: 420px;
    background: #fff;
    border-radius: 16px;
    padding: 35px;
    box-shadow: 0 10px 30px rgba(0,0,0,.08);
}
.login-card h2 {
    margin-bottom: 10px;
    text-align: center;
}
.subtitle {
    text-align: center;
    color: #666;
    margin-bottom: 30px;
}
.form-group {
    margin-bottom: 20px;
}
.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
}
.form-group input {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 15px;
}
.button {
    width: 100%;
    justify-content: center;
}
.alert {
    background: #fee2e2;
    color: #991b1b;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 20px;
}
</style>
@endsection