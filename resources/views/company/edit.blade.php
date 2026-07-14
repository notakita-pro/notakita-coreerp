@extends('layouts.app')

@section('content')
<div class="profile-container">

    <!-- ================= HEADER HALAMAN ================= -->
    <div class="page-header">
        <h2 class="page-title">Profil Perusahaan</h2>
        <p class="page-subtitle">Informasi Perusahaan Anda di platform Elbeje Tekno ERP</p>
    </div>


    <!-- ================= FORM EDIT PROFIL ================= -->
   <form method="POST"
      action="{{ route('company.profile.update', ['token' => $token]) }}"
      class="modern-form">
        @csrf

        <div class="form-section">
            <h3 class="section-title">Informasi Dasar</h3>

            <div class="form-grid">
                <!-- INPUT NAMA PERUSAHAAN -->
                <div class="form-group">
                    <label for="name">Nama Perusahaan</label>
                    <input type="text"
                           id="name"
                           name="name"
                           class="form-control"
                           value="{{ old('name', $company->name) }}"
                           placeholder="Masukkan nama resmi perusahaan">
                </div>

                <!-- INPUT WHATSAPP (DISABLED) -->
                <div class="form-group">
                    <label for="phone">Nomor WhatsApp</label>
                    <input type="text"
                           id="phone"
                           class="form-control disabled-input"
                           value="{{ $company->phone }}"
                           disabled>
                    <small class="input-help">
                        ℹ️ Nomor WhatsApp otomatis mengikuti nomor pengirim.
                    </small>
                </div>
            </div>
        </div>

        <!-- ================= TOMBOL AKSI ================= -->
        <div class="form-actions">
            <button type="submit" class="btn-action btn-submit">
                <span>💾</span> Simpan Perubahan
            </button>
            <a href="{{ route('company.dashboard', ['token' => $token]) }}"
   class="btn-action btn-cancel">
                Kembali
            </a>
        </div>

    </form>
</div>

<!-- ==========================================================
   CSS INJECTION KHUSUS HALAMAN EDIT PROFIL PERUSAHAAN
========================================================== -->
<style>
    .profile-container {
        max-width: 700px; /* Ukuran form dibuat lebih ramping & intim */
        margin: 0 auto;
    }

    .modern-form {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    /* Notifikasi Alert Estetik */
    .alert-success {
        background-color: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #065f46;
        padding: 14px 18px;
        border-radius: var(--radius-md);
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.05);
    }

    .alert-icon {
        font-size: 18px;
    }

    .alert-content {
        font-size: 14px;
    }

    /* Bagian Kontainer Form */
    .form-section {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 28px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
    }

    .section-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--secondary);
        margin-bottom: 24px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--bg-global);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    /* Penataan Kolom (1 Kolom Penuh untuk Form Profil) */
    .form-grid {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group label {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-main);
    }

    /* Elemen Input */
    .form-control {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        font-family: var(--font-sans);
        font-size: 14px;
        color: var(--text-main);
        background-color: #fff;
        transition: all 0.2s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
    }

    /* Input Terkunci */
    .disabled-input {
        background-color: #f8fafc;
        color: var(--text-muted);
        cursor: not-allowed;
        border-style: dashed;
    }

    .input-help {
        font-size: 12px;
        color: var(--text-muted);
        line-height: 1.4;
    }

    /* Tombol Aksi */
    .form-actions {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 14px 28px;
        border-radius: var(--radius-md);
        font-family: var(--font-sans);
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
        justify-content: center;
    }

    .btn-submit {
        background-color: var(--primary);
        color: #fff;
        flex: 1; /* Membuat tombol simpan mengambil porsi lebih besar */
    }

    .btn-submit:hover {
        background-color: var(--primary-hover);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
    }

    .btn-cancel {
        background-color: transparent;
        color: var(--text-muted);
        border: 1px solid var(--border-color);
        padding-left: 20px;
        padding-right: 20px;
    }

    .btn-cancel:hover {
        background-color: #f1f5f9;
        color: var(--text-main);
    }

    /* ==========================================================
       GEN-Z RESPONSIVE MOBILE ADJUSTMENT
    ========================================================== */
    @media (max-width: 768px) {
        .form-section {
            padding: 20px;
        }

        .form-actions {
            flex-direction: column;
            width: 100%;
        }

        .btn-action {
            width: 100%;
        }

        .btn-cancel {
            order: 2; /* Menaruh tombol 'Kembali' di bawah saat mobile */
        }
    }
</style>
@endsection
