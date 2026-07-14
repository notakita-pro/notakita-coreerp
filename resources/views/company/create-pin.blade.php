<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1">

    <title>Buat PIN Dashboard</title>

    <style>

        body{

            margin:0;

            padding:40px;

            background:#f5f7fb;

            font-family:Arial,Helvetica,sans-serif;

        }

        .card{

            max-width:420px;

            margin:auto;

            background:#fff;

            padding:30px;

            border-radius:12px;

            box-shadow:0 10px 30px rgba(0,0,0,.08);

        }

        h2{

            margin-top:0;

            text-align:center;

        }

        p{

            color:#666;

            text-align:center;

        }

        label{

            display:block;

            margin-top:20px;

            margin-bottom:8px;

            font-weight:bold;

        }

        input{

            width:100%;

            padding:12px;

            border:1px solid #ddd;

            border-radius:8px;

            font-size:18px;

            box-sizing:border-box;

        }

        button{

            width:100%;

            margin-top:25px;

            padding:14px;

            background:#2563eb;

            color:white;

            border:none;

            border-radius:8px;

            font-size:16px;

            cursor:pointer;

        }

        button:hover{

            background:#1d4ed8;

        }

        .error{

            background:#fee2e2;

            color:#991b1b;

            padding:10px;

            border-radius:8px;

            margin-bottom:15px;

        }

    </style>

</head>

<body>

<div class="card">

    <h2>Dashboard Perusahaan</h2>

    <p>

        <strong>{{ $company->name }}</strong>

        <br><br>

        Demi keamanan, silakan buat PIN 6 digit.

    </p>

    @if ($errors->any())

        <div class="error">

            <ul style="margin:0;padding-left:18px;">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    <form method="POST">

        @csrf

        <label>PIN 6 Digit</label>

        <input
            type="password"
            name="pin"
            maxlength="6"
            inputmode="numeric"
            pattern="[0-9]{6}"
            autocomplete="new-password"
            required>

        <label>Konfirmasi PIN</label>

        <input
            type="password"
            name="pin_confirmation"
            maxlength="6"
            inputmode="numeric"
            pattern="[0-9]{6}"
            autocomplete="new-password"
            required>

        <button type="submit">

            Simpan PIN

        </button>

    </form>

</div>

</body>
</html>