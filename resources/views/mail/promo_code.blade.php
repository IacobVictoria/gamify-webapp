<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promo Code Granted</title>
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
        <h2>🎉 Hello, {{ $user->name }}!</h2>
        <p>Congratulations! You have reached <strong>{{ $tier }} medal</strong> and unlocked a <strong>{{ $discount }}%</strong> discount.</p>
        <p>Your exclusive promo code is:</p>
        <p class="promo-code">{{ $promoCode }}</p>
        <p>Use this code at checkout to redeem your discount.</p>
        <p>Keep shopping and earning more rewards! 🚀</p>
        <div class="footer">
            <p>Thank you for being a valued customer.</p>
        </div>
    </div>
</body>
</html>
