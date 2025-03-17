<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raport Activitate Jocuri si Quiz - {{ $reportData['month'] }}</title>
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
    <h1>Raport Activitate Jocuri si Quiz - {{ $reportData['month'] }}</h1>

    <!-- 1. Cele mai populare dificultati -->
    <h2>Cele mai populare dificultati</h2>
    <table>
        <tr>
            <th>Dificultate</th>
            <th>Numar incercari</th>
        </tr>
        @foreach ($reportData['most_popular_difficulties'] as $difficulty)
            <tr>
                <td>{{ $difficulty['difficulty'] }}</td>
                <td class="center">{{ $difficulty['total_attempts'] }}</td>
            </tr>
        @endforeach
    </table>
    <p><strong>De ce este util?</strong> Ne ajuta sa vedem ce nivel de dificultate prefera utilizatorii si daca trebuie ajustata distributia quiz-urilor.</p>

    <!-- 2. Quiz-uri cu cele mai multe incercari -->
    <h2>Quiz-uri cu cele mai multe incercari</h2>
    <table>
        <tr>
            <th>Quiz</th>
            <th>Total incercari</th>
        </tr>
        @foreach ($reportData['most_attempted_quizzes'] as $quiz)
            <tr>
                <td>{{ $quiz['title'] }}</td>
                <td class="center">{{ $quiz['total_attempts'] }}</td>
            </tr>
        @endforeach
    </table>
    <p><strong>De ce este util?</strong> Aceste date ne indica care quiz-uri sunt atractive pentru utilizatori si unde ar trebui sa ne concentram eforturile de imbunatatire.</p>

    <!-- 3. Medie quiz-uri finalizate per utilizator -->
    <h2>Medie quiz-uri finalizate per utilizator</h2>
    <p class="center highlight"><strong>{{ $reportData['average_quizzes_completed'] }}</strong> quiz-uri finalizate per utilizator.</p>
    <p><strong>De ce este util?</strong> Ne ajuta sa intelegem cat de angajati sunt utilizatorii si daca trebuie sa oferim stimulente pentru finalizarea mai multor quiz-uri.</p>

    <br> <!-- Spatiu adaugat pentru separare mai clara -->

    <!-- 4. Rata de succes a quiz-urilor -->
    <h2>Rata de succes a quiz-urilor</h2>
    <table>
        <tr>
            <th>Quiz</th>
            <th>Rata de succes</th>
        </tr>
        @foreach ($reportData['quiz_success_rate'] as $quiz)
            <tr>
                <td>{{ $quiz['quiz_title'] }}</td>
                <td class="center">{{ $quiz['total_successful_attempts'] }}</td>
            </tr>
        @endforeach
    </table>
    <p><strong>De ce este util?</strong> Daca prea multi utilizatori nu reusesc sa finalizeze un quiz cu un scor bun, poate fi prea dificil sau neclar. Acest lucru ne ajuta sa imbunatatim continutul quiz-urilor.</p>

    <!-- 5. Rata de finalizare a jocurilor Hangman -->
    <h2>Rata de finalizare a jocurilor Hangman</h2>
    <p class="center highlight"><strong>{{ $reportData['hangman_completion_rate'] }}</strong> dintre jocurile Hangman au fost finalizate.</p>
    <p><strong>De ce este util?</strong> Daca rata de finalizare este scazuta, este posibil ca utilizatorii sa isi piarda interesul sau sa gaseasca jocul prea greu. Acest lucru ne ajuta sa imbunatatim mecanicile jocului.</p>

    <!-- 6. Media incercarilor per quiz -->
    <h2>Media incercarilor per quiz</h2>
    <p class="center highlight"><strong>{{ $reportData['average_quiz_retries'] }}</strong> incercari in medie per quiz.</p>
    <p><strong>De ce este util?</strong> Daca un quiz este refacut de foarte multe ori, ar putea fi fie prea dificil, fie foarte captivant. Ne ajuta sa determinam daca trebuie sa ajustam nivelul de dificultate sau sa imbunatatim explicatiile.</p>

</body>

</html>
