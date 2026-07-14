@if(isset($membership))

@php
    $badgeClass = match($membership['type']) {
        'gold'   => 'membership-gold',
        'silver' => 'membership-silver',
        default  => 'membership-free',
    };
@endphp

<div class="membership-card">

    <div class="membership-header">

        <div>

            <div class="membership-title">
                Paket Keanggotaan
            </div>

            <div class="membership-subtitle">
                Status akun perusahaan Anda
            </div>

        </div>

        <div class="membership-name {{ $badgeClass }}">
            {{ $membership['name'] }}
        </div>

    </div>
     </div>

    <div class="membership-body">

        <div class="membership-row">

            <span>Sisa Kuota Scan</span>

            <strong>

                @if($membership['quota']==-1)

                    Unlimited

                @else

                    {{ number_format($membership['remaining']) }}
                    /
                    {{ number_format($membership['quota']) }}

                @endif

            </strong>

        </div>

        @if($membership['quota']!=-1)

            <div class="quota-bar">

                <div
                    class="quota-fill"
                    style="
                        width:{{ $membership['progress'] }}%;
                        background:{{ $membership['color'] }};
                    ">
                </div>

            </div>

            <div class="quota-text">

                {{ $membership['progress'] }}%
                kuota telah digunakan

            </div>

        @endif

        <div class="membership-feature-grid">

            <div class="feature-box">

                <div class="feature-title">
                    Excel
                </div>

                <div class="feature-value">
                    {!! $membership['excel'] ? '✅' : '🔒' !!}
                </div>

            </div>

            <div class="feature-box">

                <div class="feature-title">
                    PDF
                </div>

                <div class="feature-value">
                    {!! $membership['pdf'] ? '✅' : '🔒' !!}
                </div>

            </div>

            <div class="feature-box">

                <div class="feature-title">
                    AI
                </div>

                <div class="feature-value">
                    {!! $membership['bpo'] ? '✅' : '🔒' !!}
                </div>

            </div>

        </div>

    </div>

</div>

@endif


<style>

.membership-card{

    background:#ffffff;

    border:1px solid var(--border-color);

    border-radius:22px;

    padding:22px;

    margin-bottom:20px;

    box-shadow:var(--shadow-sm);

}

.membership-card .membership-header{

    display:flex;

    justify-content:space-between;

    align-items:flex-start;

    gap:18px;

    margin-bottom:20px;

}

.membership-card .membership-title{

    font-size:20px;

    font-weight:700;

    color:var(--text-main);

}

.membership-card .membership-subtitle{

    margin-top:4px;

    font-size:13px;

    color:var(--text-muted);

}

.membership-card .membership-name{

    padding:8px 16px;

    border-radius:999px;

    color:#fff;

    font-size:13px;

    font-weight:700;

    white-space:nowrap;

}

.membership-card .membership-free{

    background:#64748b;

}

.membership-card .membership-silver{

    background:#3b82f6;

}

.membership-card .membership-gold{

    background:linear-gradient(135deg,#f59e0b,#d97706);

}

.membership-card .membership-row{

    display:flex;

    justify-content:space-between;

    align-items:center;

    margin-bottom:14px;

    font-size:15px;

}

.membership-card .membership-row span{

    color:var(--text-muted);

}

.membership-card .quota-bar{

    width:100%;

    height:12px;

    background:#e2e8f0;

    border-radius:999px;

    overflow:hidden;

}

.membership-card .quota-fill{

    height:100%;

    border-radius:999px;

    transition:width .35s ease;

}

.membership-card .quota-text{

    margin-top:8px;

    margin-bottom:20px;

    font-size:13px;

    color:var(--text-muted);

}

.membership-card .membership-feature-grid{

    display:grid;

    grid-template-columns:repeat(3,1fr);

    gap:14px;

}

.membership-card .feature-box{

    background:#f8fafc;

    border:1px solid var(--border-color);

    border-radius:16px;

    padding:14px;

    text-align:center;

    transition:.25s;

}

.membership-card .feature-box:hover{

    transform:translateY(-3px);

    box-shadow:var(--shadow-sm);

}

.membership-card .feature-title{

    font-size:13px;

    color:var(--text-muted);

    margin-bottom:10px;

}

.membership-card .feature-value{

    font-size:22px;

}

@media(max-width:768px){

    .membership-card{

        padding:18px;

        border-radius:18px;

    }

    .membership-card .membership-header{

        flex-direction:column;

        align-items:flex-start;

    }

    .membership-card .membership-name{

        align-self:flex-start;

    }

    .membership-card .membership-feature-grid{

        gap:10px;

    }

}

</style>