<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📄 Your Invoice is Ready!</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
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
            color: #007bff;
            text-align: center;
        }
        .order-info {
            padding: 15px;
            background: #f8f9fa;
            border-left: 5px solid #28a745;
            margin: 10px 0;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        ul li {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        ul li:last-child {
            border-bottom: none;
        }
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
    <h2>📄 Your Invoice is Ready!</h2>

    <p>Hello, <strong>{{ $user->name }}</strong>!</p>
    
    <p>We have generated your invoice for your recent order <strong>#{{ $order->id }}</strong>. Below are your order details:</p>

    <div class="order-info">
        <p><strong>🛍️ Order Total:</strong> ${{ number_format($order->total_price, 2) }}</p>
        <p><strong>📅 Order Date:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>
    </div>

    <p>You can download your invoice by clicking the button below:</p>

    <a href="{{ $invoiceUrl }}" class="cta-button">📥 Download Invoice</a>

    <p class="footer">Thank you for shopping with us! 🚀</p>
</div>

</body>
</html>
