<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice</title>
    <link rel="stylesheet" href="/css/invoice.css" type="text/css">
</head>

<body>
    <table class="table-no-border">
        <tr>
            <td class="width-70">
                <img src="/images/best.jpg" alt="" width="200" />
            </td>
            <td class="width-30">
                <h2>Invoice ID: {{ $order->id }}</h2>
            </td>
        </tr>
    </table>

    <div class="margin-top">
        <table class="table-no-border">
            <tr>
                <td class="width-50">
                    <div><strong>To:</strong></div>
                    <div>{{ $order->first_name }} {{ $order->last_name }}</div>
                    <div>{{ $order->address }}, {{ $order->city }}, {{ $order->state }} - {{ $order->zip_code }}</div>
                    <div><strong>Phone:</strong> {{ $order->phone }}</div>
                    <div><strong>Email:</strong> {{ $order->email }}</div>
                </td>
                <td class="width-50">
                    <div><strong>From:</strong></div>
                    <div>Your Company Name</div>
                    <div>Your Company Address</div>
                    <div><strong>Phone:</strong> Your Company Phone</div>
                    <div><strong>Email:</strong> Your Company Email</div>
                </td>
            </tr>
        </table>
    </div>

    <div>
        <table class="product-table">
            <thead>
                <tr>
                    <th class="width-50"><strong>Product</strong></th>
                    <th class="width-25"><strong>Qty</strong></th>
                    <th class="width-25"><strong>Price Per Product</strong></th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->products as $product)
                    <tr>
                        <td class="width-50">{{ $product->name }}</td>
                        <td class="width-25">{{ $product->pivot->quantity }}</td>
                        <td class="width-25">{{ number_format($product->pivot->price, 2) }} $</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td class="width-70" colspan="2"><strong>Sub Total:</strong></td>
                    <td class="width-25"><strong>{{ number_format($order->total_price, 2) }} $</strong></td>
                </tr>
                <tr>
                    <td class="width-70" colspan="2"><strong>Tax</strong> (10%):</td>
                    <td class="width-25"><strong>{{ number_format($order->total_price * 0.10, 2) }} $</strong></td>
                </tr>
                <tr>
                    <td class="width-70" colspan="2"><strong>Total Amount:</strong></td>
                    <td class="width-25"><strong>{{ number_format($order->total_price * 1.10, 2) }} $</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="footer-div">
        <p>Thank you, <br />Your Company Name</p>
    </div>
</body>

</html>