<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Login Dashboard</title>

    <style>

        :root{

            --primary-blue:#1e40af;

            --primary-hover:#1d4ed8;

            --accent-yellow:#facc15;

            --accent-yellow-hover:#eab308;

            --bg-dark-overlay:rgba(15,23,42,.60);

            --text-main:#1e293b;

            --text-muted:#64748b;

        }

        body{

            margin:0;

            padding:0;

            min-height:100vh;

            background:#f1f5f9;

            font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif;

            display:flex;

            align-items:center;

            justify-content:center;

            background-image:

                radial-gradient(circle at 0% 0%,#dbeafe 0%,transparent 50%),

                radial-gradient(circle at 100% 100%,#fef9c3 0%,transparent 50%);

        }

        .lightbox-container{

            position:fixed;

            inset:0;

            display:flex;

            justify-content:center;

            align-items:center;

            padding:16px;

            box-sizing:border-box;

            background:var(--bg-dark-overlay);

            backdrop-filter:blur(8px);

            -webkit-backdrop-filter:blur(8px);

            z-index:9999;

        }

        .card{

            width:100%;

            max-width:400px;

            background:linear-gradient(214deg,#6ca0ff,#dae6b3);

            border-radius:24px;

            padding:32px 24px;

            border:1px solid rgba(255,255,255,.7);

            box-shadow:

                0 20px 25px -5px rgba(0,0,0,.10),

                0 10px 10px -5px rgba(0,0,0,.04);

            animation:floatUp .35s ease;

        }

        @keyframes floatUp{

            from{

                transform:translateY(30px) scale(.96);

                opacity:0;

            }

            to{

                transform:translateY(0) scale(1);

                opacity:1;

            }

        }

        @keyframes shake{

            0%{transform:translateX(0)}

            20%{transform:translateX(-8px)}

            40%{transform:translateX(8px)}

            60%{transform:translateX(-6px)}

            80%{transform:translateX(6px)}

            100%{transform:translateX(0)}

        }

        .card.shake{

            animation:shake .35s;

        }

        h2{

            margin:0 0 6px;

            text-align:center;

            color:var(--text-main);

            font-size:22px;

            font-weight:700;

        }

        p{

            margin:0 0 24px;

            text-align:center;

            color:var(--text-muted);

            font-size:14px;

        }

        p strong{

            display:inline-block;

            margin-top:4px;

            padding:4px 10px;

            border-radius:12px;

            color:var(--primary-blue);

            background:#eff6ff;

            border:1px solid #dbeafe;

        }

        .bt{

            background:#ffffff70;

            border-radius:25px;

            padding:3px;

            margin-left:-15px;

            margin-right:5px;

        }

        .alert,

        .lock{

            margin-bottom:20px;

            padding:14px;

            border-radius:16px;

            font-size:14px;

            line-height:1.5;

        }

        .alert{

            background:#fef2f2;

            border:1px solid #fee2e2;

            color:#991b1b;

        }

        .lock{

            background:#fffbeb;

            border:1px solid #fef3c7;

            color:#92400e;

            text-align:center;

        }

        .pin-dots-container{

            display:flex;

            justify-content:center;

            gap:16px;

            margin:28px 0;

        }

        .pin-dot{

            width:16px;

            height:16px;

            border-radius:50%;

            border:2px solid #cbd5e1;

            transition:.15s;

        }

        .pin-dot.active{

            background:var(--primary-blue);

            border-color:var(--primary-blue);

            transform:scale(1.15);

            box-shadow:0 0 8px rgba(30,64,175,.40);

        }

        .loading{

            display:none;

            margin-bottom:16px;

            text-align:center;

            color:var(--primary-blue);

            font-weight:600;

            font-size:14px;

        }

        .loading.show{

            display:block;

        }

        .keypad{

            display:grid;

            grid-template-columns:repeat(3,1fr);

            gap:14px;

            margin-top:24px;

        }

        .key{

            height:60px;

            border-radius:16px;

            border:4px solid #c2c2c291;

            background:#f8fafc;

            color:var(--text-main);

            font-size:22px;

            font-weight:600;

            cursor:pointer;

            display:flex;

            justify-content:center;

            align-items:center;

            transition:.1s;

            user-select:none;

            -webkit-tap-highlight-color:transparent;

        }

        .key:active{

            transform:scale(.94);

            background:#e2e8f0;

        }

        .key:disabled{

            opacity:.45;

            pointer-events:none;

        }

        .forgot-link-container{

            text-align:center;

            margin-top:22px;

        }

        .forgot-link{

            color:var(--primary-blue);

            text-decoration:none;

            font-size:14px;

            font-weight:600;

        }

        .forgot-link:hover{

            color:var(--primary-hover);

            text-decoration:underline;

        }

    </style>

</head>

<body>

<div class="lightbox-container">

    <div
        class="card"
        id="loginCard"
    >

        <h2>

            <span class="bt">🔒</span>

            PIN Keamanan Sistem

        </h2>

        <p>

            <strong>

                Perusahaan :

                {{ $company->name ?? 'Nama Perusahaan' }}

            </strong>

        </p>

        @if(session('success'))

            <div
                style="
                    background:#dcfce7;
                    color:#166534;
                    border:1px solid #bbf7d0;
                    border-radius:16px;
                    padding:14px;
                    margin-bottom:20px;
                    text-align:center;
                    font-size:14px;
                "
            >

                {{ session('success') }}

            </div>

        @endif

        @if($locked ?? false)

            <div class="lock">

                Dashboard dikunci.

                <br>

                Silakan coba lagi dalam

                <strong>{{ $remaining }} menit</strong>

            </div>

        @endif

        @if($errors->any())

            <div class="alert">

                @foreach($errors->all() as $error)

                    <div>{{ $error }}</div>

                @endforeach

            </div>

        @endif

        @if(!($locked ?? false))

        <form
            method="POST"
            id="pinForm"
        >

            @csrf

            <input
                type="hidden"
                id="hiddenPin"
                name="pin"
                required
            >

            <div
                id="loadingIndicator"
                class="loading"
            >

                ⏳ Memverifikasi PIN...

            </div>

            <div class="pin-dots-container">

                <div class="pin-dot"></div>

                <div class="pin-dot"></div>

                <div class="pin-dot"></div>

                <div class="pin-dot"></div>

                <div class="pin-dot"></div>

                <div class="pin-dot"></div>

            </div>

            <div
                class="keypad"
                id="keypad"
            >

                <button type="button" class="key" onclick="appendNumber(1)">1</button>

                <button type="button" class="key" onclick="appendNumber(2)">2</button>

                <button type="button" class="key" onclick="appendNumber(3)">3</button>

                <button type="button" class="key" onclick="appendNumber(4)">4</button>

                <button type="button" class="key" onclick="appendNumber(5)">5</button>

                <button type="button" class="key" onclick="appendNumber(6)">6</button>

                <button type="button" class="key" onclick="appendNumber(7)">7</button>

                <button type="button" class="key" onclick="appendNumber(8)">8</button>

                <button type="button" class="key" onclick="appendNumber(9)">9</button>

                <button
                    type="button"
                    class="key"
                    style="background:red;color:white;font-size:18px;"
                    onclick="closeBrowser()"
                >

                    Cancel

                </button>

                <button type="button" class="key" onclick="appendNumber(0)">0</button>

                <button type="button" class="key" onclick="backspacePin()">⌫</button>

            </div>

        </form>

        @endif

        <div class="forgot-link-container">

            <a
                href="#"
                class="forgot-link"
                onclick="alert('Silakan hubungi administrator untuk melakukan reset PIN.')"
            >

                LUPA PIN?

            </a>

        </div>

    </div>

</div>
<script>

let currentPin = "";

const maxPinLength = 6;

const form = document.getElementById('pinForm');

const hiddenInput = document.getElementById('hiddenPin');

const dots = document.querySelectorAll('.pin-dot');

const keypad = document.getElementById('keypad');

const loading = document.getElementById('loadingIndicator');

const loginCard = document.getElementById('loginCard');

let submitting = false;

/*
|--------------------------------------------------------------------------
| Disable / Enable Keypad
|--------------------------------------------------------------------------
*/

function disableKeypad(){

    submitting = true;

    loading.classList.add('show');

    keypad.querySelectorAll('button').forEach(button=>{

        button.disabled = true;

    });

}

function enableKeypad(){

    submitting = false;

    loading.classList.remove('show');

    keypad.querySelectorAll('button').forEach(button=>{

        button.disabled = false;

    });

}

/*
|--------------------------------------------------------------------------
| Shake Animation
|--------------------------------------------------------------------------
*/

function shakeCard(){

    loginCard.classList.remove('shake');

    void loginCard.offsetWidth;

    loginCard.classList.add('shake');

}

/*
|--------------------------------------------------------------------------
| PIN
|--------------------------------------------------------------------------
*/

function appendNumber(number){

    if(submitting){

        return;

    }

    if(currentPin.length >= maxPinLength){

        return;

    }

    currentPin += number;

    updatePinUI();

}

function backspacePin(){

    if(submitting){

        return;

    }

    currentPin = currentPin.slice(0,-1);

    updatePinUI();

}

function clearPin(){

    currentPin = "";

    updatePinUI();

}

/*
|--------------------------------------------------------------------------
| Update UI
|--------------------------------------------------------------------------
*/

function updatePinUI(){

    hiddenInput.value = currentPin;

    dots.forEach((dot,index)=>{

        dot.classList.toggle(

            'active',

            index < currentPin.length

        );

    });

    /*
    |--------------------------------------------------------------------------
    | Auto Submit
    |--------------------------------------------------------------------------
    */

    if(

        currentPin.length === maxPinLength

        &&

        !submitting

    ){

        disableKeypad();

        form.submit();

    }

}

/*
|--------------------------------------------------------------------------
| Keyboard Support
|--------------------------------------------------------------------------
*/

document.addEventListener(

'keydown',

function(e){

    if(submitting){

        e.preventDefault();

        return;

    }

    if(

        e.key >= '0'

        &&

        e.key <= '9'

    ){

        appendNumber(e.key);

    }

    else if(

        e.key === 'Backspace'

    ){

        e.preventDefault();

        backspacePin();

    }

    else if(

        e.key === 'Escape'

    ){

        clearPin();

    }

}

);

/*
|--------------------------------------------------------------------------
| Close Browser
|--------------------------------------------------------------------------
*/

function closeBrowser(){

    history.back();

    setTimeout(function(){

        window.location='/';

    },300);

}

/*
|--------------------------------------------------------------------------
| Jika Login Gagal
|--------------------------------------------------------------------------
|
| Blade menampilkan error.
| Kita buat terasa seperti Mobile Banking.
|
*/

@if($errors->any())

window.addEventListener(

'load',

function(){

    shakeCard();

    setTimeout(function(){

        clearPin();

    },350);

});

@endif

</script>

</body>

</html>