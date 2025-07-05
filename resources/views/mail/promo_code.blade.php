<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cod Promoțional Oferit</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333;
        }
        .promo-code {
            font-size: 20px;
            font-weight: bold;
            background: #007bff;
            color: white;
            padding: 10px;
            display: inline-block;
            border-radius: 5px;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>🎉 Salut, {{ $user->name }}!</h2>
        <p>Felicitări! Ai obținut medalia de <strong>{{ $tier }}</strong> și ai deblocat o reducere de <strong>{{ $discount }}%</strong>.</p>
        <p>Codul tău promoțional exclusiv este:</p>
        <p class="promo-code">{{ $promoCode }}</p>
        <p>Folosește acest cod la finalizarea comenzii pentru a beneficia de reducere.</p>
        <p>Continuă să comanzi și să câștigi mai multe recompense! 🚀</p>
        <div class="footer">
            <p>Îți mulțumim că faci parte din comunitatea noastră!</p>
        </div>
    </div>
</body>
</html>
