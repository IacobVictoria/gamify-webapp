<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Factura</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.5;
            color: #333;
            margin: 20px;
            background-color: #f8f9fa;
        }
        h2 {
            color: #2c3e50;
        }
        strong {
            color: #34495e;
        }
        .table-no-border {
            width: 100%;
            margin-bottom: 20px;
        }
        .table-no-border td {
            padding: 10px;
            vertical-align: top;
        }
        .product-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .product-table th, .product-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .product-table thead {
            background-color: #2980b9;
            color: white;
            font-weight: bold;
        }
        .footer-div {
            text-align: center;
            margin-top: 30px;
            padding: 10px;
            border-top: 2px solid #2980b9;
            color: #555;
        }
    </style>
</head>

<body>
    <table class="table-no-border">
        <tr>
            <td class="width-30">
                <h2>Factura #{{ $order->id }}</h2>
                <p><em>Emisa la data de: {{ $order->placed_at }}</em></p>
            </td>
        </tr>
    </table>

    <div class="margin-top">
        <table class="table-no-border">
            <tr>
                <td class="width-50">
                    <strong>Facturat catre:</strong>
                    <p><strong>{{ $order->first_name }} {{ $order->last_name }}</strong></p>
                    <p>{{ $order->address }}, {{ $order->city }}, {{ $order->state }} - {{ $order->zip_code }}</p>
                    <p><strong>Telefon:</strong> {{ $order->phone }}</p>
                    <p><strong>Email:</strong> {{ $order->email }}</p>
                </td>
                <td class="width-50">
                    <strong>Date companie:</strong>
                    <p><strong>Crunchy Fuel</strong></p>
                    <p>Strada Aleea Campul cu flori, Nr. 10</p>
                    <p>Bucuresti, Romania</p>
                    <p><strong>Telefon:</strong> +40 123 456 789</p>
                    <p><strong>Email:</strong> crunchyFuel@munchies.com</p>
                </td>
            </tr>
        </table>
    </div>

    <div>
        <table class="product-table">
            <thead>
                <tr>
                    <th class="width-50"><strong>Produs</strong></th>
                    <th class="width-15"><strong>Cantitate</strong></th>
                    <th class="width-15"><strong>Pret unitar</strong></th>
                    <th class="width-20"><strong>Total</strong></th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->products as $product)
                    <tr>
                        <td class="width-50">{{ $product->name }}</td>
                        <td class="width-15">{{ $product->pivot->quantity }}</td>
                        <td class="width-15">{{ number_format($product->pivot->price, 2) }} $</td>
                        <td class="width-20">{{ number_format($product->pivot->quantity * $product->pivot->price, 2) }} $</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td class="width-70" colspan="3"><strong>Total Produse:</strong></td>
                    <td class="width-25"><strong>{{ number_format($order->total_price - 15, 2) }} $</strong></td>
                </tr>
                <tr>
                    <td class="width-70" colspan="3"><strong>Taxe + Transport:</strong></td>
                    <td class="width-25"><strong>15.00 $</strong></td>
                </tr>
                <tr>
                    <td class="width-70" colspan="3"><strong>Total de Plata:</strong></td>
                    <td class="width-25"><strong>{{ number_format($order->total_price, 2) }} $</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="footer-div">
        <p>Iti multumim pentru comanda!<br /><em>Pentru orice intrebari, ne poti contacta la crunchyFuel@munchies.com</em></p>
    </div>
</body>
</html>
