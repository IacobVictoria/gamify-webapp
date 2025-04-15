<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>NPS Report - {{ $reportData['npsData'][0]['surveyId'] ?? 'N/A' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            color: #333;
            padding: 40px;
        }

        /* Header */
        .header {
            background: #2C3E50;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        /* Sections */
        .section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .section h2 {
            font-size: 18px;
            color: #2C3E50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        /* Table Styles */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table th, .table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .table th {
            background: #3498db;
            color: white;
        }

        .table tr:nth-child(even) {
            background: #f2f2f2;
        }

        /* List Styles */
        ul {
            list-style-type: square;
            padding-left: 20px;
        }

        li {
            margin-bottom: 5px;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <div class="header">
        <h1>NPS Report - From {{ $reportData['startDate'] ?? 'N/A' }} to {{ $reportData['endDate'] ?? 'N/A' }}</h1>
    </div>

    <!-- GENERAL INFO -->
    <div class="section">
        <h2>General Information</h2>
        <p><strong>Start Date:</strong> {{ $reportData['startDate'] ?? 'N/A' }}</p>
        <p><strong>End Date:</strong> {{ $reportData['endDate'] ?? 'N/A' }}</p>
        <p><strong>Total Responses:</strong> {{ $reportData['npsData'][0]['totalResponses'] ?? 0 }}</p>
    </div>

    <!-- KEY METRICS -->
    <div class="section">
        <h2>Key NPS Metrics</h2>
        <table class="table">
            <tr>
                <th>Current NPS</th>
                <th>Promoters</th>
                <th>Detractors</th>
                <th>Passives</th>
            </tr>
            <tr>
                <td>{{ $reportData['npsData'][0]['nps'] ?? 'N/A' }}</td>
                <td>{{ $reportData['npsData'][0]['promotersCount'] ?? 0 }} ({{ $reportData['npsData'][0]['promotersPercentage'] ?? 0 }}%)</td>
                <td>{{ $reportData['npsData'][0]['detractorsCount'] ?? 0 }} ({{ $reportData['npsData'][0]['detractorsPercentage'] ?? 0 }}%)</td>
                <td>{{ $reportData['npsData'][0]['passivesCount'] ?? 0 }} ({{ $reportData['npsData'][0]['passivesPercentage'] ?? 0 }}%)</td>
            </tr>
        </table>
    </div>

    <!-- BINARY QUESTIONS -->
    <div class="section">
        <h2>Binary Questions Analysis</h2>
        @foreach($reportData['binaryQuestions'] ?? [] as $question => $data)
            <p><strong>{{ $question }}</strong>: Yes {{ $data['yes'] }}% / No {{ $data['no'] }}%</p>
        @endforeach
    </div>

    <!-- SCALE-BASED QUESTIONS -->
    <div class="section">
        <h2>Scale-Based Questions Analysis</h2>
        @foreach($reportData['scaleQuestions'] ?? [] as $question => $average)
            <p><strong>{{ $question }}</strong>: Avg. Score {{ $average }}</p>
        @endforeach
    </div>

    <!-- MULTIPLE CHOICE QUESTIONS -->
    <div class="section">
        <h2>Multiple Choice Summary (Top 3)</h2>
        @foreach($reportData['multipleChoiceQuestions'] ?? [] as $question => $choices)
            <p><strong>{{ $question }}</strong>:</p>
            <ul>
                @foreach($choices as $choice)
                    <li>{{ $choice }}</li>
                @endforeach
            </ul>
        @endforeach
    </div>

    <!-- OPEN-ENDED RESPONSES -->
    <div class="section">
        <h2>Open-Ended Responses (Top 5)</h2>
        @foreach($reportData['openEndedResponses'] ?? [] as $question => $responses)
            <p><strong>{{ $question }}</strong>:</p>
            <ul>
                @foreach($responses as $response)
                    <li>{{ $response }}</li>
                @endforeach
            </ul>
        @endforeach
    </div>

</body>

</html>
