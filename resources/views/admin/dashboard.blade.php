@extends('layouts.app')

@section('title', 'Admin Center')

@section('content')

<div class="container py-4">

    {{-- ==========================================================
        HEADER
    ========================================================== --}}
    <div class="admin-header">
        <div>
            <h2>CoreERP Admin Center</h2>
            <p>
                Selamat datang,
                <strong>{{ auth()->user()->name }}</strong>
            </p>
        </div>

        <div class="header-right">
            <span class="admin-badge">
                SUPER ADMIN
            </span>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="button btn-red btn-sm">
                    Logout
                </button>
            </form>
        </div>
    </div>

    {{-- ==========================================================
        PLATFORM SUMMARY
    ========================================================== --}}
    <div class="summary-grid">
        <div class="summary-card">
            <span class="summary-number">
                {{ number_format($dashboard['company']['total']) }}
            </span>
            <span class="summary-title">
                Total Company
            </span>
        </div>

        <div class="summary-card">
            <span class="summary-number">
                {{ number_format($dashboard['membership']['active']) }}
            </span>
            <span class="summary-title">
                Membership Active
            </span>
        </div>

        <div class="summary-card">
            <span class="summary-number">
                Rp {{ number_format($dashboard['billing']['revenue'], 0, ',', '.') }}
            </span>
            <span class="summary-title">
                Revenue
            </span>
        </div>

        <div class="summary-card">
            <span class="summary-number status-ok">
                {{ $dashboard['system']['status'] }}
            </span>
            <span class="summary-title">
                System Status
            </span>
        </div>
    </div>

    {{-- ==========================================================
        PLATFORM MANAGEMENT
    ========================================================== --}}
    <h3 class="section-title">Platform Management</h3>

    <div class="admin-grid">

        {{-- COMPANY --}}
        <a href="{{ route('admin.company.index') }}" class="admin-card">
            <div class="admin-icon">🏢</div>
            <div>
                <h4>Company Manager</h4>
                <p>Kelola seluruh perusahaan serta masuk ke Dashboard Company dalam mode Administrator.</p>
            </div>
        </a>

        {{-- MEMBERSHIP --}}
        <a href="{{ route('admin.membership.index') }}" class="admin-card">
            <div class="admin-icon">💳</div>
            <div>
                <h4>Membership Center</h4>
                <p>Monitoring paket, kuota, masa aktif, upgrade, dan status membership seluruh perusahaan.</p>
            </div>
        </a>

        {{-- BILLING --}}
        <a href="{{ route('admin.billing.index') }}" class="admin-card">
            <div class="admin-icon">💰</div>
            <div>
                <h4>Billing Center</h4>
                <p>Monitoring invoice membership, pembayaran Midtrans, subscription, dan revenue platform.</p>
            </div>
        </a>

    </div>

    {{-- ==========================================================
        SYSTEM SERVICES
    ========================================================== --}}
    <h3 class="section-title">Platform Services</h3>

    <div class="admin-grid">

        {{-- AI MONITORING --}}
        <div class="admin-card">
            <span class="coming-badge">Soon</span>
            <div class="admin-icon">🤖</div>
            <div>
                <h4>AI Monitoring</h4>
                <p>Monitoring AI Advisor, AI Assistant, OCR AI, dan seluruh proses Artificial Intelligence CoreERP.</p>
            </div>
        </div>

        {{-- OCR QUEUE (FIXED HTML & ROUTE) --}}
        <a href="{{ route('admin.gemini.index') }}" class="admin-card">
            <div class="admin-icon">📄</div>
            <div>
                <h4>OCR Queue</h4>
                <p>Monitoring proses OCR Invoice, OCR Receipt, serta antrean dokumen yang diproses AI.</p>
            </div>
        </a>

        {{-- WEBHOOK MONITOR --}}
        <div class="admin-card">
            <span class="coming-badge">Soon</span>
            <div class="admin-icon">🌐</div>
            <div>
                <h4>Webhook Monitor</h4>
                <p>Monitoring callback Midtrans, API Webhook, sinkronisasi layanan, dan integrasi eksternal.</p>
            </div>
        </div>

        {{-- SYSTEM MONITOR --}}
        <div class="admin-card">
            <span class="coming-badge">Soon</span>
            <div class="admin-icon">⚙️</div>
            <div>
                <h4>System Monitor</h4>
                <p>Monitoring Queue Worker, Scheduler, Health Check, Cache, Storage, dan performa platform.</p>
            </div>
        </div>

    </div>

    {{-- ==========================================================
        PLATFORM ROADMAP
    ========================================================== --}}
    <div class="roadmap">
        <h3>CoreERP Platform Progress</h3>
        <div class="roadmap-grid">
            <div>
                <h4>Platform</h4>
                <ul>
                    <li>✅ Company Manager</li>
                    <li>✅ Membership Center</li>
                    <li>✅ Billing Center</li>
                    <li>🚧 AI Monitoring</li>
                    <li><li>✅ OCR Queue</li> </li>
                    <li>🚧 Webhook Monitor</li>
                    <li>🚧 System Monitor</li>
                </ul>
            </div>

            <div>
                <h4>ERP Modules</h4>
                <ul>
                    <li>✅ Dashboard</li>
                    <li>✅ Purchase</li>
                    <li>🚧 Sales</li>
                    <li>🚧 Inventory</li>
                    <li>🚧 Report Analytics</li>
                    <li>🚧 Debt & Receivable</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="admin-footer">
        <span>CoreERP Platform Administration</span>
        <span>Version 1.0.0-dev</span>
    </div>

</div>

@endsection

<style>
.admin-header{display:flex;justify-content:space-between;align-items:center;gap:20px;flex-wrap:wrap;margin-bottom:30px;}
.header-right{display:flex;align-items:center;gap:12px;}
.admin-header h2{margin:0;font-size:30px;font-weight:700;color:#0f172a;}
.admin-header p{margin-top:6px;color:#64748b;}
.admin-badge{display:inline-flex;align-items:center;justify-content:center;padding:8px 18px;border-radius:999px;background:#2563eb;color:#fff;font-size:13px;font-weight:700;letter-spacing:.5px;}
.summary-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:18px;margin-bottom:34px;}
.summary-card{background:#fff;border:1px solid #e2e8f0;border-radius:18px;padding:24px;text-align:center;transition:.25s;}
.summary-card:hover{transform:translateY(-3px);box-shadow:0 12px 30px rgba(15,23,42,.08);}
.summary-number{display:block;font-size:28px;font-weight:700;color:#0f172a;margin-bottom:8px;}
.summary-title{font-size:14px;color:#64748b;}
.status-ok{color:#16a34a;}
.section-title{margin:34px 0 18px;font-size:22px;font-weight:700;color:#0f172a;}
.admin-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(290px,1fr));gap:20px;}
.admin-card{position:relative;display:flex;align-items:center;gap:18px;padding:22px;background:#fff;border:1px solid #e2e8f0;border-radius:18px;text-decoration:none;color:inherit;transition:.25s;overflow:hidden;}
.admin-card:hover{transform:translateY(-4px);box-shadow:0 14px 34px rgba(15,23,42,.10);border-color:#cbd5e1;}
.admin-icon{width:66px;height:66px;border-radius:18px;display:flex;align-items:center;justify-content:center;flex-shrink:0;background:#eef2ff;font-size:30px;}
.admin-card h4{margin:0;font-size:18px;font-weight:700;color:#0f172a;}
.admin-card p{margin-top:6px;font-size:14px;line-height:1.65;color:#64748b;}
.coming-badge{position:absolute;top:14px;right:14px;padding:5px 11px;border-radius:999px;background:#f59e0b;color:#fff;font-size:11px;font-weight:700;}
.roadmap{margin-top:40px;padding:26px;background:#fff;border:1px solid #e2e8f0;border-radius:18px;}
.roadmap h3{margin:0;font-size:22px;font-weight:700;}
.roadmap-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:30px;margin-top:22px;}
.roadmap h4{margin:0 0 12px;font-size:18px;font-weight:700;color:#0f172a;}
.roadmap ul{margin:0;padding-left:20px;}
.roadmap li{margin-bottom:10px;color:#334155;}
.admin-footer{margin-top:36px;padding-top:18px;border-top:1px solid #e2e8f0;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;font-size:13px;color:#64748b;}
@media(max-width:768px){
.admin-header{align-items:flex-start;}
.header-right{width:100%;justify-content:space-between;}
.summary-grid{grid-template-columns:1fr 1fr;}
.admin-grid{grid-template-columns:1fr;}
.admin-footer{flex-direction:column;align-items:flex-start;}
}
@media(max-width:520px){
.summary-grid{grid-template-columns:1fr;}
.admin-header h2{font-size:24px;}
.admin-icon{width:58px;height:58px;font-size:26px;}
.admin-card{padding:18px;}
}
</style>