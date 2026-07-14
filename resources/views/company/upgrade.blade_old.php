@extends('layouts.app')

@section('content')

@php
    $current = strtolower($membership['type'] ?? 'free');

    $plans = config('membership');
@endphp

<div class="upgrade-container">

    {{-- =========================================
        HERO
    ========================================== --}}
    <div class="upgrade-hero">

        <h1>Membership Center</h1>

        <p>
            Tingkatkan produktivitas bisnis Anda dengan paket Membership
        </p>

        <div class="current-package">

            Paket Aktif :

            <span class="current-badge current-{{ $current }}">

                {{ $membership['name'] }}

            </span>

        </div>

    </div>

    {{-- =========================================
        MEMBERSHIP CARD
    ========================================== --}}

    <div class="pricing-grid">

        {{-- ================= FREE =================== --}}
        <div class="pricing-card {{ $current=='free' ? 'active-plan':'' }}">

            <div class="plan-header">

                <div class="plan-icon">
                    -🆓-
                </div>

                <h2>Gratis</h2>

                <div class="price">

                    Rp0

                    <small>/bulan</small>

                </div>

            </div>

            <ul class="feature-list">

                <li>✔ 15 Nota / Bulan</li>

                <li>✔Export Excel</li>

                <li>✖ Export PDF</li>

                <li>✖ Staff BPO</li>

                <li>✔ Dashboard Online</li>

            </ul>

            @if($current=='free')

                <button
                    class="button btn-secondary full-width"
                    disabled>

                    Paket Aktif

                </button>

            @else

                <button
                    class="button btn-gray full-width">

                    Downgrade

                </button>

            @endif

        </div>

        {{-- ================= SILVER =================== --}}

        <div class="pricing-card popular {{ $current=='silver' ? 'active-plan':'' }}">

            <div class="popular-badge">

                TERLARIS

            </div>

            <div class="plan-header">

                <div class="plan-icon">

                    🥈

                </div>

                <h2>Silver</h2>

                <div class="price">

                    Rp100.000

                    <small>/bulan</small>

                </div>

            </div>

            <ul class="feature-list">

                <li>✔ 150 Nota / Bulan</li>

                <li>✔ Export Excel</li>

                <li>✔ Export PDF</li>

                <li>✖ Staff BPO</li>

                <li>✔ Edit Data Sendiri</li>

            </ul>

            @if($current=='silver')

                <button
                    class="button btn-secondary full-width"
                    disabled>

                    Paket Aktif

                </button>

            @else


<form method="POST"
      action="{{ route('company.membership.upgrade', $company->access_token) }}">

    @csrf

    <input
        type="hidden"
        name="package"
        value="silver">

    <button
        type="submit"
        class="button btn-blue full-width">

        Upgrade Sekarang

    </button>

</form>

            @endif

        </div>

        {{-- ================= GOLD =================== --}}

        <div class="pricing-card premium {{ $current=='gold' ? 'active-plan':'' }}">

            <div class="plan-header">

                <div class="plan-icon">

                    👑

                </div>

                <h2>Gold</h2>

                <div class="price">

                    Rp500.000

                    <small>/bulan</small>

                </div>

            </div>

            <ul class="feature-list">

                <li>✔ Unlimited Nota</li>

                <li>✔ Export Excel</li>

                <li>✔ Export PDF</li>

                <li>✔ Pengrapian oleh Staff BPO</li>

                <li>✔ Prioritas Support</li>

            </ul>

            @if($current=='gold')

                <button
                    class="button btn-secondary full-width"
                    disabled>

                    Paket Aktif

                </button>

            @else

<form method="POST"
      action="{{ route('company.membership.upgrade', $company->access_token) }}">

    @csrf

    <input
        type="hidden"
        name="package"
        value="gold">

    <button
        type="submit"
        class="button btn-purple full-width">

        Upgrade Sekarang

    </button>

</form>

@endif

        </div>

    </div>
        {{-- =========================================
        LAYANAN BPO PROFESIONAL
    ========================================== --}}

    <div class="section-title">
        <h2>Layanan BPO Profesional</h2>
        <p>
            Serahkan pekerjaan digital kepada tim kami sehingga Anda dapat
            fokus mengembangkan bisnis.
        </p>
    </div>

    <div class="bpo-grid">

        <div class="service-card">

            <div class="service-icon">
                🌐
            </div>

            <h3>Pembuatan Website</h3>

            <div class="service-price">

                Rp1.500.000

            </div>

            <ul>

                <li>✔ Desain Profesional</li>

                <li>✔ Domain 1 Tahun</li>

                <li>✔ Hosting 1 Tahun</li>

                <li>✔ SSL Gratis</li>

                <li>✔ Instalasi & Training</li>

            </ul>

            <div class="renew-price">

                Perpanjangan Hosting + Domain

                <strong>Rp200.000 / Tahun</strong>

            </div>

            <a href="#" class="button btn-blue full-width">
                Hubungi Kami
            </a>

        </div>

        <div class="service-card">

            <div class="service-icon">
                🎬
            </div>

            <h3>Video Profile</h3>

            <div class="service-price">

                Rp500.000

            </div>

            <ul>

                <li>✔ Video HD</li>

                <li>✔ Editing Profesional</li>

                <li>✔ Cocok untuk Website</li>

                <li>✔ Siap Upload Media Sosial</li>

            </ul>

            <a href="#" class="button btn-green full-width">
                Pesan Sekarang
            </a>

        </div>

        <div class="service-card">

            <div class="service-icon">
                🎥
            </div>

            <h3>Konten Bulanan</h3>

            <div class="service-price">

                Rp1.000.000

            </div>

            <ul>

                <li>✔ 5 Video / Bulan</li>

                <li>✔ Editing</li>

                <li>✔ Caption</li>

                <li>✔ Siap Upload</li>

            </ul>

            <a href="#" class="button btn-warning full-width">
                Konsultasi
            </a>

        </div>

    </div>

    {{-- =========================================
        FAQ
    ========================================== --}}

    <div class="faq-card">

        <h2>Pertanyaan Yang Sering Ditanyakan</h2>

        <div class="faq-item">

            <h4>Apakah paket Gratis berlaku selamanya?</h4>

            <p>
                Ya. Paket Gratis dapat digunakan tanpa batas waktu
                dengan kuota maksimal 15 nota setiap bulan.
            </p>

        </div>

        <div class="faq-item">

            <h4>Kapan kuota diperbarui?</h4>

            <p>
                Kuota akan diperbarui secara otomatis setiap awal periode
                bulanan sesuai paket yang digunakan.
            </p>

        </div>

        <div class="faq-item">

            <h4>Apa keuntungan paket Gold?</h4>

            <p>
                Selain kuota tanpa batas, proses pengrapian data nota
                dilakukan oleh tim BPO CoreERP sehingga Anda tidak perlu
                melakukan koreksi manual.
            </p>

        </div>

        <div class="faq-item">

            <h4>Bagaimana cara upgrade paket?</h4>

            <p>
                Klik tombol <strong>Upgrade Sekarang</strong> atau hubungi
                Admin CoreERP melalui WhatsApp.
            </p>

        </div>

    </div>

    {{-- =========================================
        CALL TO ACTION
    ========================================== --}}

    <div class="cta-card">

        <h2>
            Siap Mengembangkan Bisnis Anda?
        </h2>

        <p>

            Upgrade Membership atau gunakan layanan BPO Profesional
            agar pekerjaan administrasi menjadi lebih cepat,
            rapi, dan efisien.

        </p>

        <div class="cta-buttons">

            <a href="#" class="button btn-blue">

                Upgrade Membership

            </a>

            <a href="#" class="button btn-green">

                Hubungi Konsultan

            </a>

        </div>

    </div>

</div>

@endsection
<style>

/* ==========================================================
   COREERP MEMBERSHIP CENTER
==========================================================*/

.upgrade-container{
    max-width:1200px;
    margin:auto;
    padding:20px;
}

/* ================= HERO ================= */

.upgrade-hero{

    background: linear-gradient(166deg, #457cd9, #ffffff, #9042ff8c);
    border-radius: 18px;
    text-align: center;
    margin-bottom: 25px;
    padding: 30px 0;
    box-shadow: 0 15px 35px rgba(37, 99, 235, .18);

}

.upgrade-hero h1{

    font-size:38px;

    margin-bottom:15px;

}

.upgrade-hero p{

    max-width:700px;

    margin:auto;

    line-height:1.7;

    opacity:.95;

}

.current-package{

    margin-top:25px;

    font-size:18px;

}

.current-badge{

    display:inline-block;

    margin-left:12px;

    padding:8px 18px;

    border-radius:30px;

    color:#fff;

    font-weight:bold;

}

.current-free{

    background:#6b7280;

}

.current-silver{

    background:#2563eb;

}

.current-gold{

    background:linear-gradient(90deg,#d97706,#facc15);

}

/* ================= PRICING ================= */

.pricing-grid{

    display:grid;

    grid-template-columns:repeat(auto-fit,minmax(320px,1fr));

    gap:28px;

    margin-bottom:60px;

}

.pricing-card{

    background:#fff;

    border-radius:18px;

    padding:20px;

    border:1px solid #e5e7eb;

    box-shadow:0 8px 25px rgba(0,0,0,.05);

    transition:.30s;

    position:relative;

}

.pricing-card:hover{

    transform:translateY(-8px);

    box-shadow:0 18px 35px rgba(0,0,0,.12);

}

.active-plan{

    border:20px solid #fff7e1c2;

}

.popular{

    transform:scale(1.03);

}

.popular:hover{

    transform:scale(1.05);

}

.popular-badge{

    position:absolute;
    top: 10px;
    left: 10px;
    background: #f9aa57;
    color: #fff;
    padding: 10px 14px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: bold

}

.plan-header{
    background: aliceblue;
    padding: 10px;

    text-align:center;

    margin-bottom:25px;

}

.plan-icon{

    font-size:50px;

    margin-bottom:12px;

}

.price{

    font-size:34px;

    color:#2563eb;

    font-weight:bold;

    margin-top:12px;

}

.price small{

    font-size:15px;

    color:#6b7280;

}

.feature-list{

    list-style:none;

    padding:0;

    margin:20px;

}

.feature-list li{

    padding:11px 0;

    border-bottom:1px dashed #ececec;

}

.full-width{

    width:100%;

}

/* ================= SECTION TITLE ================= */

.section-title{

    text-align:center;

    margin:70px 0 35px;

}

.section-title h2{

    margin-bottom:10px;

}

.section-title p{

    color:#6b7280;

}

/* ================= BPO ================= */

.bpo-grid{

    display:grid;

    grid-template-columns:repeat(auto-fit,minmax(300px,1fr));

    gap:25px;

    margin-bottom:60px;

}

.service-card{

    background:#fff;

    border-radius:18px;

    padding:30px;

    border:1px solid #e5e7eb;

    box-shadow:0 8px 20px rgba(0,0,0,.05);

    transition:.30s;

}

.service-card:hover{

    transform:translateY(-6px);

}

.service-icon{

    font-size:45px;

    margin-bottom:12px;

}

.service-price{

    color:#2563eb;

    font-size:28px;

    font-weight:bold;

    margin:18px 0;

}

.service-card ul{

    padding-left:18px;

    line-height:2;

}

.renew-price{

    background:#f8fafc;

    padding:12px;

    border-radius:10px;

    margin:20px 0;

    font-size:14px;

}

/* ================= FAQ ================= */

.faq-card{

    background:#fff;

    border-radius:18px;

    padding:35px;

    border:1px solid #e5e7eb;

    margin-bottom:45px;

}

.faq-item{

    border-bottom:1px solid #eee;

    padding:18px 0;

}

.faq-item:last-child{

    border:none;

}

.faq-item h4{

    margin-bottom:8px;

}

.faq-item p{

    color:#6b7280;

    line-height:1.8;

}

/* ================= CTA ================= */

.cta-card{

    background:linear-gradient(135deg,#0f172a,#1e293b);

    color:#fff;

    border-radius:20px;

    padding:45px;

    text-align:center;

    margin-bottom:40px;

}

.cta-card h2{

    margin-bottom:15px;

}

.cta-card p{

    max-width:700px;

    margin:auto;

    line-height:1.8;

}

.cta-buttons{

    display:flex;

    justify-content:center;

    gap:20px;

    flex-wrap:wrap;

    margin-top:30px;

}

/* ================= RESPONSIVE ================= */

@media(max-width:768px){

    .upgrade-container{

        padding:12px;

    }

    .upgrade-hero{

        padding:20px 20px;

    }

    .upgrade-hero h1{

        font-size:28px;

    }

    .pricing-grid{

        grid-template-columns:1fr;

    }

    .popular{

        transform:none;

    }

    .popular:hover{

        transform:none;

    }

    .cta-buttons{

        flex-direction:column;

    }

    .cta-buttons .button{

        width:100%;

    }

}

</style>
