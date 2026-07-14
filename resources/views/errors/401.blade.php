<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>401 - Login Diperlukan</title>

<style>

:root{

    --primary:#4f46e5;
    --blue:#06b6d4;
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

    background:white;

    border-radius:18px;

    padding:45px;

    text-align:center;

    border:1px solid var(--border);

    box-shadow:0 20px 60px rgba(0,0,0,.08);

}

.icon{

    width:110px;
    height:110px;

    margin:auto;

    border-radius:50%;

    background:#e0f2fe;

    display:flex;
    justify-content:center;
    align-items:center;

    font-size:58px;

    animation:pulse 2s infinite;

}

h1{

    margin-top:28px;

    font-size:60px;

    color:var(--blue);

    font-weight:800;

}

h2{

    margin-top:10px;

    color:var(--text);

    font-size:28px;

}

p{

    margin-top:18px;

    color:var(--muted);

    line-height:1.8;

    font-size:15px;

}

.button{

    display:inline-block;

    margin-top:35px;

    background:var(--primary);

    color:white;

    text-decoration:none;

    padding:14px 28px;

    border-radius:10px;

    font-weight:700;

    transition:.25s;

}

.button:hover{

    transform:translateY(-2px);

    box-shadow:0 8px 18px rgba(79,70,229,.3);

}

@keyframes pulse{

0%{
transform:scale(1);
}

50%{
transform:scale(1.06);
}

100%{
transform:scale(1);
}

}

</style>

</head>

<body>

<div class="card">

<div class="icon">
🔑
</div>

<h1>401</h1>

<h2>Otentikasi Diperlukan</h2>

<p>

Silakan akses melalui Link Khusus </br>
yang memiliki 16 digit token</br>
atau sesi login telah berakhir.

<br><br>


</p>

<a href="/login" class="button">
Lalu lanjutkan dengan PIN keamanan
</a>

</div>

</body>

</html>