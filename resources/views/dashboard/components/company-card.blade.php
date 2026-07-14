@if(isset($membership))

@php
    $badgeClass = match($membership['type']) {
        'gold'   => 'membership-gold',
        'silver' => 'membership-silver',
        default  => 'membership-free',
    };
@endphp

<div class="company-card">

    <div class="company-header">

        <div>
            <span class="company-title">
                Paket Aktif</span>
 
        <span class="company-badge {{ $badgeClass }}">
            {{ $membership['name'] }}
        </span>  <a
        href="{{ route('company.membership',$company->access_token) }}"
        class="company-upgrade">

        🚀 Upgrade

    </a>

    </div></div>

    <div class="company-row">

        <span>Sisa Kuota</span>

        <strong>

            @if($membership['quota']==-1)

                Unlimited

            @else

                {{ number_format($membership['remaining']) }}
                /
                {{ number_format($membership['quota']) }}
                Nota

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

    @endif

    <div class="feature-label">
    Fitur tersedia di paket ini :
    </div>

    <div class="feature-grid">

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
                Staff
            </div>

            <div class="feature-value">
                {!! $membership['bpo'] ? '✅' : '🔒' !!}
            </div>

        </div>

</div>
</div>
@endif


<style>
.company-card{

    background:#ffffff;
    border:1px solid var(--border-color);
    border-radius:20px;
    padding:20px;
    margin-bottom:18px;
    box-shadow:var(--shadow-sm);

}

.company-header{

    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:14px;
    margin-bottom:15px;

}

.company-title{

    font-size:18px;
    font-weight:700;
    color:var(--text-main);

}

.company-subtitle{

    margin-top:4px;
    font-size:13px;
    color:var(--text-muted);

}

.company-badge{

    padding:7px 14px;
    border-radius:5px;
    font-size:12px;
    font-weight:700;
    color:#fff;
    white-space:nowrap;

}

.membership-free{

    background:#64748b;

}

.membership-silver{

    background:#3b82f6;

}

.membership-gold{

    background:linear-gradient(135deg,#f59e0b,#d97706);

}

.company-row{

    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:12px;
    font-size:15px;

}

.company-row span{

    color:var(--text-muted);

}

.quota-bar{

    width:100%;
    height:10px;
    background:#e5e7eb;
    border-radius:999px;
    overflow:hidden;
    margin-bottom:18px;

}

.quota-fill{

    height:100%;
    border-radius:999px;
    transition:.35s;

}

.feature-label{

    font-size:13px;
    color:var(--text-muted);
    margin-bottom:10px;
    font-weight:600;

}

.feature-grid{

    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:12px;

}

.feature-box{

    border:1px solid var(--border-color);
    border-radius:14px;
    background:#f8fafc;
    padding:3px 2px;
    text-align:center;

}

.feature-title{

    font-size:12px;
    color:var(--text-muted);
    margin-bottom:8px;

}

.feature-value{

    font-size:22px;

}

.company-upgrade{
    position: absolute;
    right: 30px;
    margin-top : -5px;
    text-align: center;
    padding: 6px 14px 8px 10px;
    border-radius: 25px;
    background: linear-gradient(171deg, #d0cdff, #00216a);
    color: white;
    font-weight: 700;
    transition: .25s;

}

.company-upgrade:hover{

    opacity:.92;
    transform:translateY(-2px);

}

@media(max-width:768px){

    .company-card{

        padding:15px;

    }

    .company-header{

        flex-direction:column;
        align-items:flex-start;

    }

    .company-badge{

        align-self:flex-start;
        margin-left:5px;

    }

}

</style>