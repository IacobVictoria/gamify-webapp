<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raport Activitate Medalii si Insigne - {{ $reportData['month'] }}</title>
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
    <h1>Raport Activitate Medalii si Insigne - {{ $reportData['month'] }}</h1>

    <!-- 1. Timpul mediu pana la prima medalie -->
    <h2>Timpul mediu pana la prima medalie</h2>
    <p class="center highlight"><strong>{{ $reportData['average_time_to_first_medal'] }}</strong> zile</p>
    <p><strong>De ce este util?</strong> Ne ajuta sa vedem cat de repede se implica utilizatorii in activitati si daca avem nevoie de ajustari pentru a-i incuraja sa ramana activi mai repede.</p>

    <!-- 2. Insignele cel mai usor si cel mai greu de obtinut -->
    <h2>Insignele cel mai usor si cel mai greu de obtinut</h2>
    <table>
        <tr>
            <th>Tip</th>
            <th>Insigna</th>
            <th>Numar obtineri</th>
        </tr>
        <tr>
            <td>Cel mai usor obtinut</td>
            <td>{{ $reportData['easiest_and_hardest_badges']['easiest']['name'] ?? 'N/A' }}</td>
            <td class="center">{{ $reportData['easiest_and_hardest_badges']['easiest']['obtained_count'] ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td>Cel mai rar obtinut</td>
            <td>{{ $reportData['easiest_and_hardest_badges']['hardest']['name'] ?? 'N/A' }}</td>
            <td class="center">{{ $reportData['easiest_and_hardest_badges']['hardest']['obtained_count'] ?? 'N/A' }}</td>
        </tr>
    </table>
    <p><strong>De ce este util?</strong> Daca o insigna este prea usor de obtinut, s-ar putea sa nu fie perceputa ca valoroasa. Daca este prea greu de obtinut, utilizatorii s-ar putea demotiva.</p>

    <div class="page-break"></div>

    <!-- 3. Media de insigne obtinute per categorie -->
    <h2>Media de insigne obtinute per categorie</h2>
    <table>
        <tr>
            <th>Categorie</th>
            <th>Medie insigne obtinute per utilizator</th>
        </tr>
        @foreach ($reportData['average_badges_per_category'] as $category => $avg)
            <tr>
                <td>{{ $category }}</td>
                <td class="center">{{ $avg }}</td>
            </tr>
        @endforeach
    </table>
    <p><strong>De ce este util?</strong> Ne ajuta sa vedem daca exista categorii care nu sunt atractive pentru utilizatori si daca trebuie sa cream mai multe insigne pentru anumite domenii.</p>

</body>
</html>
