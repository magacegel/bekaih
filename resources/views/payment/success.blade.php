<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil - Terima Kasih</title>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #6777ef, #ffffff,#FFFFFF);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .success-container {
            text-align: center;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            max-width: 600px;
            width: 90%;
        }
        .checkmark {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: block;
            margin: 0 auto 20px;
            animation: bounce 2s infinite;
        }
        @keyframes bounce {
                0%, 100% {
                    transform: translateY(0);
                }
                50% {
                    transform: translateY(-20px);
                }
            }
        .success-title {
            color: #2c3e50;
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        .success-message {
            color: #666;
            font-size: 1.2rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        .back-button {
            background: #00b09b;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 30px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        .back-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .confetti {
            position: fixed;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 999;
        }
        .countdown {
            color: #666;
            font-size: 1rem;
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <div class="confetti" id="confetti"></div>
    
    <div class="success-container" data-aos="zoom-in">
        <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
            <!-- Hull of ship -->
            <path fill="#00b09b" d="M50 350 L462 350 L400 450 L112 450 Z"/>
            <!-- Main body -->
            <path fill="#00b09b" d="M150 250 L362 250 L362 350 L150 350 Z"/>
            <!-- Cabin -->
            <path fill="#00b09b" d="M200 150 L312 150 L312 250 L200 250 Z"/>
            <!-- Chimney -->
            <rect x="240" y="100" width="32" height="50" fill="#00b09b"/>
            <!-- Windows -->
            <circle cx="230" cy="200" r="15" fill="white"/>
            <circle cx="280" cy="200" r="15" fill="white"/>
            <!-- Water lines -->
            <path fill="none" stroke="#00b09b" stroke-width="3" d="M30 380 Q 100 360 170 380 Q 240 400 310 380 Q 380 360 450 380"/>
            <path fill="none" stroke="#00b09b" stroke-width="2" d="M40 400 Q 110 380 180 400 Q 250 420 320 400 Q 390 380 460 400"/>
        </svg>
        
        <h1 class="success-title" data-aos="fade-up" data-aos-delay="200">Pembayaran Berhasil!</h1>
        
        <p class="success-message" data-aos="fade-up" data-aos-delay="400">
            Terima kasih atas kepercayaan Anda dalam melakukan pembayaran.<br>
            Laporan Anda akan segera kami proses untuk ditinjau oleh tim surveyor kami.
        </p>
        
        <a href="{{ route('report.index') }}" class="back-button" data-aos="fade-up" data-aos-delay="600">
            Kembali ke Beranda
        </a>

        <div class="countdown" id="countdown"></div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
    <script>
        AOS.init();
        
        // Efek confetti
        confetti({
            particleCount: 100,
            spread: 70,
            origin: { y: 0.6 }
        });

        // Animasi tambahan untuk checkmark
        setTimeout(() => {
            confetti({
                particleCount: 50,
                spread: 50,
                origin: { y: 0.7 }
            });
        }, 500);

        // Timer redirect
        let timeLeft = 5;
        const countdownElement = document.getElementById('countdown');
        
        const countdownTimer = setInterval(() => {
            countdownElement.textContent = `Halaman akan dialihkan dalam ${timeLeft} detik`;
            timeLeft--;

            if (timeLeft < 0) {
                clearInterval(countdownTimer);
                window.location.href = "{{ route('report.index') }}";
            }
        }, 1000);
    </script>
</body>
</html>
