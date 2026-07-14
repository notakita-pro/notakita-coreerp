<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NOTAKITA - Asisten Virtual - ERP dalam Genggaman</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #0038bb;
            --primary-dark: #002da0;
            --text-dark: #0f172a;
            --text-muted: #475569;
            --border-muted: #e2e8f0;
        }

        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--text-dark);
            height: 100vh; /* Mengunci tinggi layar tepat 100% dari layar gadget */
            display: flex;
            justify-content: center;
            align-items: flex-start; /* MODIFIKASI: Mulai posisi flex dari atas agar perhitungan animasi pas */
            padding: 0; /* Padding dinonaktifkan agar tidak mengganggu kalkulasi ujung layar */
            
            background: linear-gradient(rgba(15, 23, 42, 0.4), rgba(15, 23, 42, 0.4)), 
                        url("{{ asset('elbejetekno.png') }}"); 

            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            overflow: hidden; /* Mutlak diperlukan agar tidak muncul scroll saat kartu di bawah */
        }

        .container {
            width: 100%;
            max-width: 400px;
            background: rgb(241 255 244 / 27%);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(10px);
            padding: 20px 15px;
            border-radius: 30px;
            box-shadow: 0 20px 40px rgb(95 186 255 / 59%);
            border: 1px solid rgba(255, 255, 255, 0.4);
            
            /* MODIFIKASI ANIMASI: Durasi diperlambat menjadi 8 detik agar pergerakan jauh terasa santai/halus */
            animation: floatFullPage 15s ease-in-out infinite;
            will-change: transform;
            
            margin: 0 10px; /* Jaga jarak aman kanan-kiri pada layar HP */
        }

        /* MODIFIKASI KEYFRAMES: 
           Menggunakan kalkulasi otomatis (calc) agar kartu bergerak dari paling atas (0vh) 
           hingga paling bawah tanpa terpotong oleh tinggi kartu itu sendiri (100% dari tinggi kartu).
        */
        @keyframes floatFullPage {
            0% {
                transform: translateY(2vh); /* Jarak aman 2% dari ujung atas layar saat mulai */
            }
            50% {
                transform: translateY(calc(98vh - 100%)); /* Bergerak ke ujung bawah layar dikurangi tinggi kartu */
            }
            100% {
                transform: translateY(2vh); /* Kembali ke atas */
            }
        }

        .badge {
            display: inline-block;
            background: #e0f2fe;
            color: #0369a1;
            padding: 6px 16px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 20px;
            letter-spacing: 0.03em;
        }

        h1 {
            font-size: 32px;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 8px;
        }

        b {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-dark);
            line-height: 1.5;
            display: inline-block;
            margin-bottom: 12px;
        }

        hr {
            border: none;
            border-top: 1px dashed var(--border-muted);
            margin: 0;
        }

        small {
            display: block;
            text-align: center;
            padding: 12px 5px;
            font-size: 18px;
            color: #ffffff;
            font-weight: 700;
            text-shadow: 0 1px 4px rgba(0, 0, 0, 0.4);
        }

        .spacer {
            height: 24px;
        }

        .btn-group {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-top: 8px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 14px 16px;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            text-align: center;
        }

        .btn-primary {
            background: var(--primary);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 56, 187, 0.25);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .btn-outline {
            background: #ffffff;
            color: #25d366; 
            border: 1px solid #25d366;
        }

        .btn-outline:hover {
            background: #f0fdf4;
            transform: translateY(-1px);
        }

        @media (max-width: 400px) {
            .container {
                padding: 10px 15px;
            }
            .btn-group {
                grid-template-columns: 1fr;
                gap: 10px;
            }
        }
    </style>
</head>
<body>

    <div class="container" id="mainCard">
        <hr><small>Diproses dengan Sistem AI</small>
        <hr><small>Diamankan dengan token dan PIN</small>
        <hr><small>Dibuatkan dashboard yang smart</small>
        <hr><small>Bisa cetak ke Excel dan PDF</small>
        <hr>
        
        <div class="spacer"></div>
        
        <div class="btn-group">
            
            <a href="https://wa.me/+6285363845316" target="_blank" class="btn btn-outline">Hubungkan Bot</a>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const card = document.getElementById("mainCard");

            // Menghentikan pergerakan saat disentuh/di-hover agar user mudah klik tombol
            card.addEventListener("mouseenter", () => card.style.animationPlayState = "paused");
            card.addEventListener("mouseleave", () => card.style.animationPlayState = "running");
            card.addEventListener("touchstart", () => card.style.animationPlayState = "paused", { passive: true });
            card.addEventListener("touchend", () => card.style.animationPlayState = "running");
        });
    </script>
</body>
</html>