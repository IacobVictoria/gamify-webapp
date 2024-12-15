<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Lista Participantilor</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            line-height: 1.6;
            background-color: #f3f4f6;
        }

        h1,
        h2 {
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .event-details {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 20px;
            margin: 20px auto;
            width: 90%;
            max-width: 900px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .event-details p {
            margin: 5px 0;
            font-size: 16px;
            color: #34495e;
        }

        .event-details strong {
            color: #2c3e50;
        }

        .stats {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 20px;
            margin: 20px auto;
            width: 90%;
            max-width: 900px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .stats h2 {
            font-size: 24px;
            color: #2980b9;
            margin-bottom: 15px;
        }

        .stats p {
            font-size: 18px;
            margin: 10px 0;
            color: #34495e;
        }

        .stats strong {
            font-weight: bold;
        }

        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        table th,
        table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #2980b9;
            color: #fff;
            font-size: 16px;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #ecf0f1;
        }

        /* Responsive Design */
        @media (max-width: 768px) {

            .event-details,
            .stats,
            table {
                width: 100%;
                padding: 15px;
            }

            table th,
            table td {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>

    <h1 style="text-align:center;">Detalii Eveniment</h1>

    <div class="event-details">
        <p><strong>Titlu:</strong> {{ $event['title'] }}</p>
        <p><strong>Descriere:</strong> {{ $event['description'] }}</p>
        <p><strong>Data Inceput:</strong> {{ $event['start'] }}</p>
        <p><strong>Data Sfarsit:</strong> {{ $event['end'] }}</p>
    </div>

    <div class="stats">
        <h2>Statistici Participanti</h2>
        <p><strong>Cei care au confirmat:</strong> {{ $confirmedCount }}
            ({{ number_format($confirmationPercentage, 2) }}%)</p>
        <p><strong>Cei care nu au confirmat:</strong> {{ $notConfirmedCount }}</p>
        <p><strong>Total Participanti:</strong> {{ $totalParticipants }}</p>
    </div>

    <h2 style="text-align:center;">Lista Participantilor</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nume</th>
                <th>Email</th>
                <th>Confirmed</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($participants as $index => $participant)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $participant['name'] }}</td>
                    <td>{{ $participant['email'] }}</td>
                    <td>
                        {{ $participant['confirmed'] == 0 ? 'x' : '✔' }}
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>