@extends('layouts.app')

@section('title', 'Verifikasi Pembayaran')

@section('content')

<div class="payment-wrapper">

    <div class="payment-card">

        <div class="payment-icon" id="paymentIcon">
            ⏳
        </div>

        <h2 id="paymentTitle">
            Memverifikasi Pembayaran
        </h2>

        <p id="paymentMessage">
            Mohon tunggu beberapa saat.<br>
            Kami sedang memastikan status pembayaran Anda.
        </p>

        <div class="payment-info">

            <div class="row-item">
                <span>Invoice</span>
                <strong>{{ $order->invoice_number }}</strong>
            </div>

            <div class="row-item">
                <span>Perusahaan</span>
                <strong>{{ $order->company->name }}</strong>
            </div>

            <div class="row-item">
                <span>Paket</span>
                <strong>{{ strtoupper($order->package) }}</strong>
            </div>

            <div class="row-item">
                <span>Status</span>

                <strong id="paymentStatus">
                    {{ $order->getStatus() }}
                </strong>

            </div>

        </div>

        <div class="loading">
            <div class="spinner"></div>
        </div>

        <div
            id="actionArea"
            style="display:none;margin-top:25px;">

            <a
                id="continueButton"
                href="#"
                class="button">
                Lanjut
            </a>

        </div>

    </div>

</div>

@endsection


@push('scripts')

<script>

const statusLabel=document.getElementById('paymentStatus');
const title=document.getElementById('paymentTitle');
const message=document.getElementById('paymentMessage');
const icon=document.getElementById('paymentIcon');
const action=document.getElementById('actionArea');
const btn=document.getElementById('continueButton');

const endpoint="{{ route('payment.status',$order) }}";

let timer=setInterval(checkStatus,2000);

async function checkStatus(){

    try{

        const response=await fetch(endpoint,{
            headers:{
                'Accept':'application/json'
            }
        });

        const data=await response.json();

        statusLabel.innerHTML=data.status;

        switch(data.status){

            case 'PAID':

                clearInterval(timer);

                icon.innerHTML='✅';

                title.innerHTML='Pembayaran Berhasil';

                message.innerHTML='Membership berhasil diaktifkan.<br>Mengalihkan ke Membership Center...';

                setTimeout(function(){

                    window.location=data.redirect;

                },1800);

            break;


            case 'PENDING':

                icon.innerHTML='⏳';

                title.innerHTML='Menunggu Konfirmasi';

                message.innerHTML='Pembayaran sedang diproses oleh Payment Gateway.';

            break;


            case 'EXPIRED':

                clearInterval(timer);

                icon.innerHTML='⌛';

                title.innerHTML='Invoice Kedaluwarsa';

                message.innerHTML='Batas waktu pembayaran telah berakhir.';

                action.style.display='block';

                btn.href=data.payment_url;

                btn.innerHTML='Bayar Ulang';

            break;


            case 'CANCELLED':

                clearInterval(timer);

                icon.innerHTML='❌';

                title.innerHTML='Invoice Dibatalkan';

                message.innerHTML='Invoice telah dibatalkan.';

            break;

        }

    }

    catch(e){

        console.log(e);

    }

}

</script>

@endpush


<style>

.payment-wrapper{
max-width:760px;
margin:40px auto;
padding:20px;
}

.payment-card{
background:#fff;
border-radius:18px;
padding:40px;
border:1px solid #e5e7eb;
text-align:center;
box-shadow:0 8px 25px rgba(0,0,0,.05);
}

.payment-icon{
font-size:64px;
margin-bottom:15px;
}

.payment-card h2{
margin-bottom:10px;
}

.payment-card p{
color:#64748b;
line-height:1.7;
}

.payment-info{
margin-top:30px;
border-top:1px solid #e5e7eb;
padding-top:20px;
}

.row-item{
display:flex;
justify-content:space-between;
padding:12px 0;
border-bottom:1px solid #f1f5f9;
}

.loading{
margin-top:25px;
display:flex;
justify-content:center;
}

.spinner{
width:44px;
height:44px;
border:5px solid #e5e7eb;
border-top:5px solid #2563eb;
border-radius:50%;
animation:spin .8s linear infinite;
}

@keyframes spin{

0%{
transform:rotate(0deg);
}

100%{
transform:rotate(360deg);
}

}

.button{
display:inline-block;
padding:12px 22px;
background:#2563eb;
color:#fff;
border-radius:10px;
text-decoration:none;
font-weight:600;
}

.button:hover{
background:#1d4ed8;
}

</style>