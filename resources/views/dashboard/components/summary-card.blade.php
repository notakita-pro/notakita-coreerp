@if(isset($dashboard['finance']))

<div class="summary-section">

    <div class="section-title">
        Kinerja Keuangan
    </div>

    <div class="summary-grid">

        <div class="summary-card">

            <div class="summary-icon">
                🛒
            </div>

            <div class="summary-content">

                <div class="summary-value">
                    Rp {{ number_format($dashboard['finance']['purchase'] ?? 0,0,',','.') }}
                </div>

                <div class="summary-label">
                    Pembelian
                </div>

            </div>

        </div>

        <div class="summary-card">

            <div class="summary-icon">
                💳
            </div>

            <div class="summary-content">

                <div class="summary-value">
                    Rp {{ number_format($dashboard['finance']['sales'] ?? 0,0,',','.') }}
                </div>

                <div class="summary-label">
                    Penjualan
                </div>

            </div>

        </div>

        <div class="summary-card">

            <div class="summary-icon">
                📉
            </div>

            <div class="summary-content">

                <div class="summary-value">
                    Rp {{ number_format($dashboard['finance']['operational_cost'] ?? 0,0,',','.') }}
                </div>

                <div class="summary-label">
                    Biaya Operasional
                </div>

            </div>

        </div>

        <div class="summary-card">

            <div class="summary-icon">
                📈
            </div>

            <div class="summary-content">

                <div class="summary-value">
                    Rp {{ number_format($dashboard['finance']['gross_profit'] ?? 0,0,',','.') }}
                </div>

                <div class="summary-label">
                    Laba Kotor
                </div>

            </div>

        </div>

        <div class="summary-card">

            <div class="summary-icon">
                🧾
            </div>

            <div class="summary-content">

                <div class="summary-value">
                    Rp {{ number_format($dashboard['finance']['receivable'] ?? 0,0,',','.') }}
                </div>

                <div class="summary-label">
                    Piutang
                </div>

            </div>

        </div>

        <div class="summary-card">

            <div class="summary-icon">
                🏦
            </div>

            <div class="summary-content">

                <div class="summary-value">
                    Rp {{ number_format($dashboard['finance']['payable'] ?? 0,0,',','.') }}
                </div>

                <div class="summary-label">
                    Utang
                </div>

            </div>

        </div>

        <div class="summary-card highlight">

            <div class="summary-icon">
                💰
            </div>

            <div class="summary-content">

                <div class="summary-value">
                    Rp {{ number_format($dashboard['finance']['net_profit'] ?? 0,0,',','.') }}
                </div>

                <div class="summary-label">
                    Laba Bersih
                </div>

            </div>

        </div>

    </div>

</div>

<style>

.summary-section{

    margin-bottom:24px;

}

.summary-section .section-title{

    font-size:18px;
    font-weight:700;
    color:#0f172a;
    margin-bottom:16px;

}

.summary-grid{

    display:grid;

    grid-template-columns:repeat(auto-fit,minmax(240px,1fr));

    gap:16px;

}

.summary-card{

    background:#fff;

    border:1px solid #e2e8f0;

    border-radius:18px;

    padding:18px;

    display:flex;

    align-items:center;

    gap:14px;

    transition:.25s;

    box-shadow:0 8px 24px rgba(15,23,42,.05);

}

.summary-card:hover{

    transform:translateY(-4px);

    border-color:#c7d2fe;

    box-shadow:0 16px 32px rgba(37,99,235,.12);

}

.summary-card.highlight{

    background:linear-gradient(135deg,#eff6ff,#eef2ff);

    border:2px solid #2563eb;

}

.summary-icon{

    width:54px;

    height:54px;

    border-radius:16px;

    background:linear-gradient(135deg,#eef2ff,#dbeafe);

    display:flex;

    align-items:center;

    justify-content:center;

    font-size:24px;

    flex-shrink:0;

}

.summary-content{

    flex:1;

}

.summary-value{

    font-size:22px;

    font-weight:800;

    color:#1e293b;

    line-height:1.2;

}

.summary-label{

    margin-top:5px;

    font-size:13px;

    color:#64748b;

}

@media(max-width:768px){

    .summary-grid{

        grid-template-columns:1fr;

    }

    .summary-card{

        padding:16px;

    }

    .summary-icon{

        width:48px;

        height:48px;

        font-size:22px;

    }

    .summary-value{

        font-size:20px;

    }

}

</style>

@endif