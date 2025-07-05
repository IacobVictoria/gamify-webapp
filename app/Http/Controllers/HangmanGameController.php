<?php

namespace App\Http\Controllers;

use App\Events\GameEnded;
use App\Events\GameReady;
use App\Events\GameStarted;
use App\Events\GameUpdated;
use App\Events\OpponentJoined;
use App\Models\HangmanSession;
use App\Models\User;
use App\Services\Badges\HangmanBadgeService;
use App\Services\UserScoreService;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class HangmanGameController extends Controller
{
    public $userScoreService, $badgeService;
    public function __construct(UserScoreService $userScoreService, HangmanBadgeService $badgeService)
    {
        $this->userScoreService = $userScoreService;
        $this->badgeService = $badgeService;
    }
    public function index()
    {
        return Inertia::render('HangmanGame/Index');
    }
    public function generateGameSession()
    {

        $user = Auth::user();

        // Creează o sesiune de joc
        $session = HangmanSession::create([
            'id' => Uuid::uuid(),
            'creator_id' => $user->id,
            'turn' => $user->id,
        ]);
        return redirect()->route('user.hangman.game.show', ['sessionId' => $session->id]);

    }
    public function show($sessionId)
    {
        $session = HangmanSession::findOrFail($sessionId);

        if ($session->completed) {
            return redirect()->route('user.dashboard')->with('errorMessage', 'Această sesiune de joc a fost deja finalizată.');
        }

        return Inertia::render('HangmanGame/Start', [
            'sessionId' => $session->id,
            'creatorId' => $session->creator_id,
            'turn' => $session->turn,
        ]);
    }
    public function joinSession(Request $request, $sessionId)
    {
        $session = HangmanSession::findOrFail($sessionId);
        $user = Auth::user();

        // Verifică dacă utilizatorul care accesează este deja creator
        if ($session->creator_id === $user->id) {
            return response()->json([
                'creator_name' => $session->creator->name,
                'opponent_name' => $session->opponent ? $session->opponent->name : 'Așteptăm...',
                'opponent_connected' => $session->opponent_id !== null,
            ]);
        }

        // Adaugă oponentul dacă nu există deja
        if (!$session->opponent_id) {
            $session->opponent_id = $user->id;
            $session->save();
            broadcast(new OpponentJoined($session->id, $user->name))->toOthers();
        }

        return response()->json([
            'creator_name' => $session->creator->name,
            'opponent_name' => $session->opponent ? $session->opponent->name : 'Waiting...',
            'opponent_connected' => $session->opponent_id !== null,
        ]);
    }

    public function startGame($sessionId)
    {
        $session = HangmanSession::findOrFail($sessionId);

        if (Auth::id() !== $session->creator_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Emite evenimentul pentru ambii jucători
        broadcast(new GameStarted($sessionId))->toOthers();

        return response()->json(['message' => 'Game started successfully']);
    }
    public function submitWord(Request $request, $sessionId)
    {
        $session = HangmanSession::findOrFail($sessionId);
        $user = Auth::user();

        $word = $request->input('word');

        // Citim JSON-ul pentru a găsi hint-ul asociat
        $wordData = Storage::disk('s3')->get('hangman/word_options_aws.json');
        $words = json_decode($wordData, true);

        // Căutăm hint-ul pentru cuvântul selectat
        $hint = collect($words)->firstWhere('word', $word)['hint'] ?? "No hint available";

        if ($user->id === $session->creator_id) {
            $session->word_for_opponent = $word;
            $session->hint_for_opponent = $hint;
        } elseif ($user->id === $session->opponent_id) {
            $session->word_for_creator = $word;
            $session->hint_for_creator = $hint;
        } else {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $session->save();
        // Verifică dacă ambele cuvinte și hint-uri au fost introduse
        $bothWordsReady = !empty($session->word_for_creator) && !empty($session->word_for_opponent) &&
            !empty($session->hint_for_creator) && !empty($session->hint_for_opponent);

        if ($bothWordsReady) {
            // Notifică frontend-ul că jocul poate începe
            broadcast(new GameReady($session->id));
        }

        return response()->json([
            'message' => 'Word and hint saved successfully',
            'bothWordsReady' => $bothWordsReady
        ]);

    }
    public function handleGuess(Request $request, $sessionId)
    {
        //guessedletters sunt toate si corecte si gresite

        $session = HangmanSession::findOrFail($sessionId);
        $user = Auth::user();

        $letter = strtoupper($request->input('letter'));
        $isCorrect = false;

        // determin dacă jucatorul curent este creator sau oponent
        $isCreator = $user->id === $session->creator_id;
        $currentWord = strtoupper($isCreator ? $session->word_for_creator : $session->word_for_opponent);

        $guessedLettersKey = $isCreator ? 'guessed_letters_creator' : 'guessed_letters_opponent';
        $mistakesKey = $isCreator ? 'mistakes_creator' : 'mistakes_opponent';

        $guessedLetters = json_decode($session->$guessedLettersKey, true) ?? [];
        $mistakes = $session->$mistakesKey;

        //Verifică dacă litera e în cuvânt
        if (!in_array($letter, $guessedLetters)) {
            $guessedLetters[] = $letter;
            if (strpos($currentWord, $letter) === false) {
                $mistakes++;
            } else {
                $isCorrect = true;
            }
        }

        $guessedLetters = array_unique($guessedLetters);

        // Salvează modificările
        $session->$guessedLettersKey = json_encode($guessedLetters);
        $session->$mistakesKey = $mistakes;
        $session->save();

        //Termin jocul daca sunt toate ghicite sau nr de greseli e mare
        $allLettersGuessed = $this->areAllLettersGuessed($currentWord, $guessedLetters);

        $finished = $allLettersGuessed || $mistakes >= ceil(strlen($currentWord) / 2);

        if ($finished) {
            $this->handleRoundCompletion($session, $isCreator, $allLettersGuessed);

            // noua runda resetam keyboard
            broadcast(new GameUpdated(
                $session->id,
                $session->turn,
                [],
                [],
                [],
                0,
                0
            ))->toOthers();
        } else {
            $correctLetters = $this->getCorrectLetters($currentWord, $guessedLetters);
            $wrongLetters = $this->getWrongLetters($currentWord, $guessedLetters);

            broadcast(new GameUpdated(
                $session->id,
                $session->turn,
                $correctLetters,
                $wrongLetters,
                $guessedLetters,
                $session->mistakes_creator,
                $session->mistakes_opponent
            ))->toOthers();
        }

        return response()->json([
            'correct' => $isCorrect,
            'finished' => $finished,
            'nextTurn' => $session->turn,
            'errors' => $mistakes,
        ]);
    }

    private function getCorrectLetters(string $word, array $guessedLetters): array
    {
        $correctLetters = [];
        foreach ($guessedLetters as $letter) {
            if (strpos($word, $letter) !== false) {
                $correctLetters[] = $letter;
            }
        }
        return $correctLetters;
    }

    private function areAllLettersGuessed(string $word, array $guessedLetters): bool
    {
        foreach (str_split($word) as $char) {
            if (ctype_alpha($char) && !in_array($char, $guessedLetters)) {
                return false;
            }
        }
        return true;
    }

    private function getWrongLetters(string $word, array $guessedLetters): array
    {
        $wrongLetters = [];
        foreach ($guessedLetters as $letter) {
            if (strpos($word, $letter) === false) {
                $wrongLetters[] = $letter;
            }
        }
        return $wrongLetters;
    }

    //finalizeaza sf rundei
    private function handleRoundCompletion($session, bool $isCreator, bool $allLettersGuessed): void
    {

        if ($allLettersGuessed || $session->mistakes_creator >= ceil(strlen($session->word_for_creator) / 2) || $session->mistakes_opponent >= ceil(strlen($session->word_for_opponent) / 2)) {
            if ($isCreator) {
                // Schimbăm turul la oponent
                $session->turn = $session->opponent_id;
            } else {
                // Dacă este rândul oponentului, jocul se încheie
                $session->completed = true;
            }
            if ($session->completed) {
                // Calcul scoruri vs greseli
                $maxMistakesCreator = ceil(strlen($session->word_for_creator) / 2);
                $maxMistakesOpponent = ceil(strlen($session->word_for_opponent) / 2);

                $scores = $this->calculateScores(
                    $session->mistakes_creator,
                    $maxMistakesCreator,
                    $session->mistakes_opponent,
                    $maxMistakesOpponent
                );

                // Salveaza scoruri in bd
                $creator = User::find($session->creator_id);
                $opponent = User::find($session->opponent_id);
                // Save scores in the session
                $session->scores = json_encode([
                    'creator' => [
                        'name' => $session->creator->name,
                        'score' => $scores['creator']
                    ],
                    'opponent' => [
                        'name' => $session->opponent->name ?? 'Opponent',
                        'score' => $scores['opponent']
                    ]
                ]);
                broadcast(new GameEnded($session->id, json_decode($session->scores, true)));
                $this->userScoreService->awardPointsBasedOnHangmanScore($creator, $scores['creator']);
                $this->badgeService->checkAndAssignBadges($creator);
                $this->badgeService->checkAndAssignBadges($opponent);
                $this->userScoreService->awardPointsBasedOnHangmanScore($opponent, $scores['opponent']);
            }

            $session->save();
        }
    }

    private function calculateScores(int $mistakesCreator, int $maxMistakesCreator, int $mistakesOpponent, int $maxMistakesOpponent): array
    {
        $creatorPointsPerMistake = 100 / $maxMistakesCreator;
        $opponentPointsPerMistake = 100 / $maxMistakesOpponent;

        $creatorScore = 100 - ($mistakesCreator * $creatorPointsPerMistake);
        $opponentScore = 100 - ($mistakesOpponent * $opponentPointsPerMistake);

        return [
            'creator' => max(0, round($creatorScore)),
            'opponent' => max(0, round($opponentScore)),
        ];
    }


    public function getWordOptions()
    {
        // În AWS S3 ai un JSON cu cuvinte și hint-uri
        $wordData = Storage::disk('s3')->get('hangman/word_options_aws.json');
        $words = json_decode($wordData, true);

        // Amestecă lista de cuvinte
        shuffle($words);

        // Selectează primele 6 cuvinte și împarte-le între jucători
        $creatorWords = array_slice($words, 0, 3);
        $opponentWords = array_slice($words, 4, 3);

        return response()->json([
            'creatorWords' => $creatorWords,
            'opponentWords' => $opponentWords,
        ]);
    }


}