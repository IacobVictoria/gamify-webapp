<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raport Activitate Utilizatori - {{ $reportData['month'] }}</title>
    <style>
   <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            padding: 20px;
            background-color: #f9f9f9;
        }
        h1 {
            text-align: center;
            font-size: 26px;
            font-weight: bold;
            color: #34495e;
            margin-bottom: 20px;
        }
        h2 {
            font-size: 20px;
            margin-top: 30px;
            color: #2c3e50;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background-color: #fff;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
            font-size: 14px;
        }
        th {
            background-color: #f4f4f4;
            font-weight: bold;
            color: #2c3e50;
        }
        .highlight {
            font-weight: bold;
            color: #27ae60;
        }
        .small-text {
            font-size: 14px;
            color: gray;
            text-align: center;
        }
        .center {
            text-align: center;
        }
        .section {
            padding: 15px;
            margin-bottom: 20px;
            background-color: #ecf0f1;
            border-radius: 5px;
        }
    </style>
    </style>
</head>
<body>
    <h1>Raport Activitate Utilizatori - {{ $reportData['month'] }}</h1>

    <h2>Utilizatori</h2>
    <table>
        <tr><th>Metrica</th><th>Valoare</th></tr>
        <tr><td>Numar de utilizatori noi</td><td class="highlight">{{ $reportData['new_users_count'] }}</td></tr>
        <tr><td>Timp mediu între înregistrare si prima achizitie</td><td>{{ $reportData['avg_days_to_first_order'] }} zile</td></tr>
    </table>

    <h2>Comenzi și Vanzari</h2>
    <table>
        <tr><th>Metrica</th><th>Valoare</th></tr>
        <tr><td>Media comenzilor per utilizator</td><td>{{ $reportData['avg_orders_per_user'] }}</td></tr>
        <tr><td>Valoarea medie a cosului</td><td>{{ number_format($reportData['avg_order_value'], 2) }} RON</td></tr>
        <tr><td>Comenzi cu reducere</td><td>{{ $reportData['orders_with_discount'] }}</td></tr>
        <tr><td>Comenzi fara reducere</td><td>{{ $reportData['orders_without_discount'] }}</td></tr>
    </table>

    <h2>Interactiuni pe Platforma</h2>
    <table>
        <tr><th>Metrica</th><th>Valoare</th></tr>
        <tr><td>Media recenziilor per utilizator</td><td>{{ $reportData['avg_reviews_per_user'] }}</td></tr>
        <tr><td>Media like-urilor per recenzie</td><td>{{ $reportData['avg_likes_per_review'] }}</td></tr>
    </table>

    <h2>Top Produse în Wishlist</h2>
    @if(!empty($reportData['top_wishlist_products']))
        <table>
            <tr><th>Produs</th><th>Numar de adaugari în wishlist</th></tr>
            @foreach ($reportData['top_wishlist_products'] as $product)
                <tr>
                    <td>{{ $product['name'] ?? 'N/A' }}</td>
                    <td class="center">{{ $product['wishlist_count'] ?? 0 }}</td>
                </tr>
            @endforeach
        </table>
    @else
        <p class="small-text center">📌 Nu exista produse adaugate în wishlist în această luna.</p>
    @endif

</body>
</html>
