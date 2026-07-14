@if(!empty($company) && isset($dashboard['wallet']))

<div class="wallet-card">

    <div class="wallet-top">

        <div>

            <div class="wallet-label">
                Ringkasan Bisnis
            </div>

            <div class="wallet-balance">
                Rp {{ number_format($dashboard['wallet']['purchase_total'] ?? 0,0,',','.') }}
            </div>

            <div class="wallet-caption">
                Ringkasan aktivitas bisnis yang telah tercatat
            </div>

        </div>

        <div class="wallet-icon">
            📊
        </div>

    </div>

    <div class="wallet-divider"></div>

    <div class="wallet-grid">

        <div class="wallet-item">

            <div class="wallet-value">
                {{ number_format($dashboard['wallet']['transaction_count'] ?? 0) }}
            </div>

            <div class="wallet-text">
                Transaksi
            </div>

        </div>

        <div class="wallet-item">

            <div class="wallet-value">
                {{ number_format($dashboard['wallet']['supplier_count'] ?? 0) }}
            </div>

            <div class="wallet-text">
                Supplier
            </div>

        </div>

        <div class="wallet-item">

            <div class="wallet-value">
                {{ number_format($dashboard['wallet']['product_count'] ?? 0) }}
            </div>

            <div class="wallet-text">
                Produk
            </div>

        </div>

    </div>

</div>

<style>

.wallet-card{

    background:linear-gradient(135deg,#4f46e5,#2563eb);
    color:#fff;

    border-radius:22px;

    padding:22px;

    margin-bottom:22px;

    overflow:hidden;

    position:relative;

    box-shadow:0 18px 35px rgba(37,99,235,.28);

}

.wallet-card::before{

    content:'';

    position:absolute;

    width:180px;
    height:180px;

    border-radius:50%;

    background:rgba(255,255,255,.08);

    right:-70px;
    top:-70px;

}

.wallet-card::after{

    content:'';

    position:absolute;

    width:120px;
    height:120px;

    border-radius:50%;

    background:rgba(255,255,255,.05);

    left:-40px;
    bottom:-40px;

}

.wallet-top{

    position:relative;
    z-index:2;

    display:flex;

    justify-content:space-between;

    align-items:flex-start;

    gap:20px;

}

.wallet-label{

    font-size:13px;

    letter-spacing:1px;

    text-transform:uppercase;

    opacity:.85;

}

.wallet-balance{

    margin-top:8px;

    font-size:34px;

    font-weight:800;

    line-height:1.15;

}

.wallet-caption{

    margin-top:8px;

    font-size:13px;

    opacity:.85;

}

.wallet-icon{

    width:60px;
    height:60px;

    border-radius:18px;

    display:flex;

    align-items:center;

    justify-content:center;

    background:rgba(255,255,255,.16);

    backdrop-filter:blur(8px);

    font-size:28px;

}

.wallet-divider{

    position:relative;

    z-index:2;

    height:1px;

    margin:22px 0;

    background:rgba(255,255,255,.18);

}

.wallet-grid{

    position:relative;

    z-index:2;

    display:grid;

    grid-template-columns:repeat(3,1fr);

    gap:14px;

}

.wallet-item{

    background:rgba(255,255,255,.10);

    border:1px solid rgba(255,255,255,.12);

    border-radius:16px;

    padding:14px;

    text-align:center;

    backdrop-filter:blur(6px);

}

.wallet-value{

    font-size:22px;

    font-weight:800;

    margin-bottom:4px;

}

.wallet-text{

    font-size:12px;

    opacity:.88;

    text-transform:uppercase;

    letter-spacing:.6px;

}

@media(max-width:768px){

    .wallet-card{

        border-radius:18px;

        padding:18px;

    }

    .wallet-balance{

        font-size:28px;

    }

    .wallet-grid{

        gap:10px;

    }

    .wallet-item{

        padding:12px 8px;

    }

    .wallet-value{

        font-size:19px;

    }

    .wallet-text{

        font-size:11px;

    }

    .wallet-icon{

        font-size:24px;

    }

}

</style>

@endif