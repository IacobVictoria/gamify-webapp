<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raport Vanzari si Stocuri - {{ $reportData['month'] }}</title>
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

        th,
        td {
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
</head>

<body>
    <h1>Raport Vanzari si Stocuri - {{ $reportData['month'] }}</h1>

    <h2>Top 10 Produse Vandute</h2>
    <table>
        <tr>
            <th>Produs</th>
            <th>Cantitate Vanduta</th>
        </tr>
        @foreach ($reportData['top_10_sold_products'] as $product)
            <tr>
                <td>{{ $product['name'] }}</td>
                <td class="center">{{ $product['total_sold'] }}</td>
            </tr>
        @endforeach
    </table>

    <h2>Cele mai Putin Vandute Produse</h2>
    <table>
        <tr>
            <th>Produs</th>
            <th>Cantitate Vanduta</th>
        </tr>
        @foreach ($reportData['least_sold_products'] as $product)
            <tr>
                <td>{{ $product['name'] }}</td>
                <td class="center">{{ $product['total_sold'] }}</td>
            </tr>
        @endforeach
    </table>

    <h2>Stocuri Fluctuante</h2>
    <table>
        <tr>
            <th>Produs</th>
            <th>Variatie Stoc</th>
        </tr>
        @foreach ($reportData['stock_fluctuations'] as $product)
            <tr>
                <td>{{ $product['name'] }}</td>
                <td class="center"><?php    echo e($product['stock_variation']); ?></td>
            </tr>
        @endforeach
    </table>

    <h2>Statistici Vanzari</h2>
    <table>
        <tr>
            <td>Media zilnica</td>
            <td>{{ $reportData['daily_sales_avg'] }} produse</td>
        </tr>
        <tr>
            <td>Media saptamanala</td>
            <td>{{ $reportData['weekly_sales_avg'] }} produse</td>
        </tr>
        <tr>
            <td>Timp mediu pana la epuizare</td>
            <td>{{ $reportData['avg_days_to_out_of_stock'] }} zile</td>
        </tr>
    </table>

</body>

</html>