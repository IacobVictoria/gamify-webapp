<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raport Activitate Produse - {{ $reportData['period'] }} : Start: {{ $reportData['startDate'] }}- End: {{ $reportData['endDate'] }}</title>
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

        .center {
            text-align: center;
        }

        .page-break {
            page-break-before: always;
            margin-top: 40px;
        }
    </style>
</head>

<body>
    <h1>Raport Activitate Produse - {{ $reportData['period'] }} : Start: {{ $reportData['startDate'] }}- End: {{ $reportData['endDate'] }}</h1>

    <!-- 1. Cea mai vanduta categorie de produse -->
    <h2>Cea mai vanduta categorie</h2>
    <p class="center highlight">
        Categoria: <strong>{{ $reportData['best_selling_category']['category'] ?? 'N/A' }}</strong> <br>
        Total produse vandute: <strong>{{ $reportData['best_selling_category']['total_sold'] ?? 0 }}</strong>
    </p>
    <p><strong>De ce este util?</strong> Ne ajuta sa identificam ce tip de produse au cea mai mare cerere si sa
        optimizam stocurile.</p>

    <!-- 2. Produsele cu cele mai mari vanzari -->
    <h2>Top 10 produse vandute</h2>
    <table>
        <tr>
            <th>Produs</th>
            <th>Total vandut</th>
        </tr>
        @foreach ($reportData['top_selling_products'] as $product)
            <tr>
                <td>{{ $product['product_name'] }}</td>
                <td class="center">{{ $product['total_sold'] }}</td>
            </tr>
        @endforeach
    </table>
    <p><strong>De ce este util?</strong> Ne ajuta sa vedem care produse performeaza cel mai bine si sa planificam
        campanii promotionale.</p>

    <!-- 3. Vanzari produse noi vs. produse vechi -->
    <h2>Produse noi vs. produse vechi</h2>
    <p class="center highlight">
        Produse noi vandute: <strong>{{ $reportData['new_vs_old_products_sales']['new_products_sales'] }}</strong> <br>
        Produse vechi vandute: <strong>{{ $reportData['new_vs_old_products_sales']['old_products_sales'] }}</strong>
        <br>
        Concluzie: <strong>{{ $reportData['new_vs_old_products_sales']['comparison'] }}</strong>
    </p>
    <p><strong>De ce este util?</strong> Ne arata daca utilizatorii sunt mai interesati de produse noi sau de cele deja
        existente.</p>

    <!-- 4. Produse cumparate cu oferte speciale vs. fara oferte -->
    <h2>Vanzari cu discount vs. vanzari standard</h2>
    <p class="center highlight">
        Produse cu discount: <strong>{{ $reportData['discount_vs_regular_sales']['discounted_sales'] }}</strong> <br>
        Produse fara discount: <strong>{{ $reportData['discount_vs_regular_sales']['regular_sales'] }}</strong> <br>
        Rata utilizare discount: <strong>{{ $reportData['discount_vs_regular_sales']['discount_usage_rate'] }}</strong>
    </p>
    <p><strong>De ce este util?</strong> Ne ajuta sa vedem cat de eficiente sunt campaniile promotionale si daca
        utilizatorii prefera ofertele.</p>

    <!-- 5. Produse cu cel mai bun si cel mai slab rating -->
    <h2>Cel mai bun si cel mai slab produs din recenzii</h2>
    <table>
        <tr>
            <th>Tip</th>
            <th>Produs</th>
            <th>Rating</th>
        </tr>
        <tr>
            <td>Cel mai bun</td>
            <td>{{ $reportData['best_and_worst_rated_products']['best_rated_product']['name'] ?? 'N/A' }}</td>
            <td class="center">
                {{ $reportData['best_and_worst_rated_products']['best_rated_product']['rating'] ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td>Cel mai slab</td>
            <td>{{ $reportData['best_and_worst_rated_products']['worst_rated_product']['name'] ?? 'N/A' }}</td>
            <td class="center">
                {{ $reportData['best_and_worst_rated_products']['worst_rated_product']['rating'] ?? 'N/A' }}</td>
        </tr>
    </table>
    <p><strong>De ce este util?</strong> Ne permite sa vedem ce produse sunt apreciate de clienti si unde trebuie sa
        facem imbunatatiri.</p>

    <!-- 6. Produse adaugate cel mai des in wishlist -->
    <h2>Top 10 produse adaugate in wishlist</h2>
    <table>
        <tr>
            <th>Produs</th>
            <th>Total adaugari</th>
        </tr>
        @foreach ($reportData['most_wishlisted_products'] as $product)
            <tr>
                <td>{{ $product['product_name'] }}</td>
                <td class="center">{{ $product['total_wishlisted'] }}</td>
            </tr>
        @endforeach
    </table>
    <p><strong>De ce este util?</strong> Ne arata ce produse sunt dorite de clienti si ne ajuta sa planificam oferte
        pentru acestea.</p>

</body>

</html>