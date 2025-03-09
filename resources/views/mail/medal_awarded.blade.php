<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🏅 Congratulations, {{ $user->name }}!</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #ff9800;
        }
        .medal {
            font-size: 80px;
            margin: 20px 0;
        }
        .bronze { color: #cd7f32; }
        .silver { color: #c0c0c0; }
        .gold { color: #ffd700; }
        .cta-button {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 12px;
            text-align: center;
            background-color: #28a745;
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
        }
        .cta-button:hover {
            background-color: #218838;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #888;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="email-container">
    <h2>🏅 Congratulations, {{ $user->name }}!</h2>

    <p>You have just earned a <strong>{{ $tier }} Medal</strong> for your amazing performance! 🎉</p>

    <div class="medal {{ strtolower($tier) }}">
        @if($tier == 'Gold')
            🥇
        @elseif($tier == 'Silver')
            🥈
        @else
            🥉
        @endif
    </div>

    <p>Keep up the great work and aim for the next milestone!</p>

    <div>🔥 View Your Medals in App!</div>

    <p class="footer">Thank you for being part of our community! 🚀</p>
</div>

</body>
</html>
