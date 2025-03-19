<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice</title>
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
                <h2>Invoice #{{ $order->id }}</h2>
                <p><em>Issued on: {{ $order->placed_at}}</em></p>
            </td>
        </tr>
    </table>

    <div class="margin-top">
        <table class="table-no-border">
            <tr>
                <td class="width-50">
                    <strong>Billing To:</strong>
                    <p><strong>{{ $order->first_name }} {{ $order->last_name }}</strong></p>
                    <p>{{ $order->address }}, {{ $order->city }}, {{ $order->state }} - {{ $order->zip_code }}</p>
                    <p><strong>Phone:</strong> {{ $order->phone }}</p>
                    <p><strong>Email:</strong> {{ $order->email }}</p>
                </td>
                <td class="width-50">
                    <strong>Company Details:</strong>
                    <p><strong>Crunchy Fuel</strong></p>
                    <p>Strada Aleea Campul cu flori, Nr.10</p>
                    <p>Bucuresti, Romania</p>
                    <p><strong>Phone:</strong> +40 123 456 789</p>
                    <p><strong>Email:</strong> crunchyFuel@munchies.com</p>
                </td>
            </tr>
        </table>
    </div>

    <div>
        <table class="product-table">
            <thead>
                <tr>
                    <th class="width-50"><strong>Product</strong></th>
                    <th class="width-15"><strong>Qty</strong></th>
                    <th class="width-15"><strong>Unit Price</strong></th>
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
                    <td class="width-70" colspan="3"><strong>Sub Total:</strong></td>
                    <td class="width-25"><strong>{{ number_format($order->total_price - 15, 2) }} $</strong></td>
                </tr>
                <tr>
                    <td class="width-70" colspan="3"><strong>Taxes + Shipping Fee:</strong></td>
                    <td class="width-25"><strong>15.00 $</strong></td>
                </tr>
                <tr>
                    <td class="width-70" colspan="3"><strong>Total Amount:</strong></td>
                    <td class="width-25"><strong>{{ number_format($order->total_price, 2) }} $</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="footer-div">
        <p>Thank you for your order!<br /><em>For any questions, contact us at crunchyFuel@munchies.com</em></p>
    </div>
</body>
</html>
