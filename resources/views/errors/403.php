<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>403 - Akses Ditolak</title>

<style>

:root{
    --primary:#4f46e5;
    --green:#10b981;
    --bg:#f6f8fa;
    --card:#ffffff;
    --text:#1e293b;
    --muted:#64748b;
    --border:#e2e8f0;
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

    width:100%;
    max-width:520px;

    background:var(--card);

    border-radius:18px;

    padding:45px;

    text-align:center;

    box-shadow:0 20px 60px rgba(0,0,0,.08);

    border:1px solid var(--border);

}

.lock{

    width:110px;
    height:110px;

    margin:auto;

    border-radius:50%;

    background:#eef2ff;

    display:flex;
    justify-content:center;
    align-items:center;

    font-size:58px;

    animation:float 2.5s ease-in-out infinite;

}

h1{

    margin-top:30px;

    font-size:60px;

    color:var(--primary);

    font-weight:800;

}

h2{

    margin-top:8px;

    font-size:28px;

    color:var(--text);

}

p{

    margin-top:18px;

    color:var(--muted);

    line-height:1.8;

    font-size:15px;

}

.button{

    margin-top:35px;

    display:inline-block;

    padding:14px 28px;

    border-radius:10px;

    text-decoration:none;

    background:var(--primary);

    color:white;

    font-weight:700;

    transition:.25s;

}

.button:hover{

    transform:translateY(-2px);

    box-shadow:0 8px 20px rgba(79,70,229,.3);

}

@keyframes float{

0%{
transform:translateY(0px);
}

50%{
transform:translateY(-10px);
}

100%{
transform:translateY(0px);
}

}

</style>

</head>

<body>

<div class="card">

<div class="lock">
🔒
</div>

<h1>403</h1>

<h2>Akses Ditolak</h2>

<p>

Maaf, Anda tidak memiliki izin untuk membuka halaman ini.

<br><br>

Silakan hubungi administrator</br> 
atau Login menggunakan akun Anda.

</p>

<a href="/" class="button">
Kembali ke Beranda
</a>

</div>

</body>
</html>