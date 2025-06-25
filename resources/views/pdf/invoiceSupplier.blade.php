<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .header,
        .footer {
            text-align: center;
            padding: 10px;
            background: #f4f4f4;
        }

        .content {
            margin: 20px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .table th {
            background: #f4f4f4;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Factura</h1>
        <p>Comanda: {{ $invoiceData['order']->id }}</p>
        <p>Data de: {{ $invoiceData['order']->order_date }}</p>
    </div>
    <div class="content">
        <h3>Detalii Furnizor</h3>
        <p>Nume Furnizor: {{ $invoiceData['supplierName'] }}</p>

        <h3>Produse</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Produs</th>
                    <th>Cantitate</th>
                    <th>Pret</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoiceData['products'] as $product)
                    @if (isset($product['productData']) && is_object($product['productData']))
                        <tr>
                            <td>{{ $product['productData']->name }}</td>
                            <td>{{ $product['quantity'] }}</td>
                            <td>{{ number_format($product['productData']->price, 2) }} RON</td>
                            <td>{{ number_format($product['productData']->price * $product['quantity'], 2) }} RON</td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="4">Produs invalid sau lipsa</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>

        </table>

        <h3>Total: {{ number_format($invoiceData['order']->total_price, 2) }} RON</h3>
    </div>
    <div class="footer">
        <p>&copy; {{ date('Y') }} CrunchyFuel. Toate drepturile rezervate.</p>
    </div>
</body>

</html>