<div class="ai-card">

    <div class="ai-badge">
        AI Assistant
    </div>

    <div class="ai-title">
        Tanyakan Bisnis Anda
    </div>

    <div class="ai-description">

        Gunakan AI CoreERP untuk membantu membaca nota,
        menganalisis laporan, menghitung keuntungan,
        serta memberikan rekomendasi bisnis secara otomatis.

    </div>

    <a href="#" class="ai-button">

        <span>🤖</span>

        Tanya AI

    </a>

</div>

<style>

.ai-card{

    position:relative;

    overflow:hidden;

    margin-top:26px;

    margin-bottom:24px;

    padding:28px;

    border-radius:24px;

    background:linear-gradient(135deg,#4f46e5 0%,#2563eb 55%,#06b6d4 100%);

    color:#ffffff;

    box-shadow:0 22px 45px rgba(37,99,235,.28);

}

.ai-card::before{

    content:'';

    position:absolute;

    width:180px;

    height:180px;

    border-radius:50%;

    background:rgba(255,255,255,.08);

    right:-60px;

    top:-60px;

}

.ai-card::after{

    content:'';

    position:absolute;

    width:120px;

    height:120px;

    border-radius:50%;

    background:rgba(255,255,255,.06);

    left:-30px;

    bottom:-40px;

}

.ai-badge{

    position:relative;

    z-index:2;

    display:inline-block;

    padding:6px 14px;

    border-radius:30px;

    background:rgba(255,255,255,.16);

    backdrop-filter:blur(8px);

    font-size:12px;

    font-weight:700;

    letter-spacing:.5px;

    text-transform:uppercase;

    margin-bottom:18px;

}

.ai-title{

    position:relative;

    z-index:2;

    font-size:28px;

    font-weight:800;

    line-height:1.2;

    margin-bottom:14px;

}

.ai-description{

    position:relative;

    z-index:2;

    font-size:15px;

    line-height:1.8;

    opacity:.95;

    max-width:520px;

    margin-bottom:24px;

}

.ai-button{

    position:relative;

    z-index:2;

    display:inline-flex;

    align-items:center;

    gap:10px;

    padding:14px 24px;

    background:#ffffff;

    color:#2563eb;

    text-decoration:none;

    border-radius:14px;

    font-weight:700;

    transition:.25s;

}

.ai-button:hover{

    transform:translateY(-3px);

    box-shadow:0 12px 28px rgba(0,0,0,.18);

}

.ai-button span{

    font-size:18px;

}

@media(max-width:768px){

    .ai-card{

        padding:22px;

        border-radius:20px;

    }

    .ai-title{

        font-size:22px;

    }

    .ai-description{

        font-size:14px;

        line-height:1.7;

    }

    .ai-button{

        width:100%;

        justify-content:center;

    }

}

</style>