<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>419 - Sesi Berakhir</title>

<style>

:root{

--bg:#f6f8fa;
--card:#fff;

--text:#1e293b;
--muted:#64748b;

--primary:#f59e0b;
--border:#e5e7eb;

}

*{
margin:0;
padding:0;
box-sizing:border-box;
}

body{

font-family:Inter,Arial,sans-serif;
background:var(--bg);

display:flex;
justify-content:center;
align-items:center;

min-height:100vh;

padding:20px;

}

.card{

max-width:540px;
width:100%;

background:white;

border-radius:20px;

padding:45px;

text-align:center;

border:1px solid var(--border);

box-shadow:0 18px 45px rgba(0,0,0,.08);

}

.icon{

font-size:70px;

animation:rotate 2s ease infinite;

}

h1{

margin-top:20px;

font-size:64px;

font-weight:800;

color:var(--primary);

}

h2{

margin-top:10px;

font-size:30px;

}

p{

margin-top:18px;

font-size:15px;

line-height:1.8;

color:var(--muted);

}

.button{

display:inline-block;

margin-top:35px;

padding:14px 30px;

background:var(--primary);

color:white;

text-decoration:none;

font-weight:700;

border-radius:10px;

transition:.25s;

}

.button:hover{

transform:translateY(-2px);

box-shadow:0 10px 20px rgba(245,158,11,.25);

}

@keyframes rotate{

0%{
transform:rotate(0deg);
}

25%{
transform:rotate(-10deg);
}

50%{
transform:rotate(10deg);
}

100%{
transform:rotate(0deg);
}

}

</style>

</head>

<body>

<div class="card">

<div class="icon">
⏳
</div>

<h1>419</h1>

<h2>Sesi Dashboard Berakhir</h2>

<p>

Demi menjaga keamanan data perusahaan,

dashboard otomatis ditutup setelah tidak ada aktivitas.

<br><br>

Silakan login kembali menggunakan PIN Anda.

</p>

<a href="/" class="button">

Login Kembali

</a>

</div>

</body>

</html>