{{-- resources/views/customer/order/success.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terima Kasih!</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(to right bottom, #fde68a, #f97316);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            padding: 20px;
        }
        .success-card {
            background-color: rgba(255, 255, 255, 0.9);
            color: #333;
            padding: 40px 30px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            width: 100%;
            animation: fadeIn 1s ease-out;
        }
        .success-icon {
            font-size: 4rem;
            color: #22c55e;
            margin-bottom: 20px;
        }
        h1 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 15px;
        }
        p {
            font-size: 1.1rem;
            margin-bottom: 25px;
            line-height: 1.6;
        }
        .back-button {
            background-color: #f97316;
            color: white;
            padding: 12px 25px;
            border-radius: 9999px;
            font-weight: bold;
            display: inline-block;
            transition: background-color 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .back-button:hover {
            background-color: #ea580c;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="success-card">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h1>Pesanan Berhasil Dibuat!</h1>
        <p>Terima kasih telah memesan di MaMinKo. Pesanan Anda sedang diproses. Silakan tunggu di meja Anda.</p>
        <a href="/maminko" class="back-button">Kembali ke Menu</a>
    </div>

    <script>
        // Hapus cart dari localStorage setelah checkout berhasil
        localStorage.removeItem('cart');
    </script>
</body>
</html>
