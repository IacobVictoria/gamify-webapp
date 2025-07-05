<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🏅 Felicitări, {{ $user->name }}!</title>
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
    <h2>🏅 Felicitări, {{ $user->name }}!</h2>

    <p>Tocmai ai câștigat o <strong>Medalie {{ $tier }}</strong> pentru performanța ta excepțională! 🎉</p>

    <div class="medal {{ strtolower($tier) }}">
        @if($tier == 'Gold')
            🥇
        @elseif($tier == 'Silver')
            🥈
        @else
            🥉
        @endif
    </div>

    <p>Continuă să faci treabă bună și țintește următoarea medalie!</p>

    <div>🔥 Vezi Medaliile Tale în Aplicație!</div>

    <p class="footer">Îți mulțumim că faci parte din comunitatea noastră! 🚀</p>
</div>

</body>
</html>
