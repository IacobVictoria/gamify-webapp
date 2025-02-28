<?php

namespace App\Http\Controllers;

use App\Events\GameEnded;
use App\Events\GameReady;
use App\Events\GameStarted;
use App\Events\GameUpdated;
use App\Events\OpponentJoined;
use App\Models\HangmanSession;
use App\Models\User;
use App\Services\BadgeService;
use App\Services\UserScoreService;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class HangmanGameController extends Controller
{
    public $userScoreService, $badgeService;
    public function __construct(UserScoreService $userScoreService, BadgeService $badgeService)
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
                'opponent_name' => $session->opponent ? $session->opponent->name : 'Waiting...',
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
        $wordData = Storage::disk('s3')->get('hangman/word_options.json');
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

        // Determinăm dacă jucătorul curent este creator sau oponent
        $isCreator = $user->id === $session->creator_id;
        $currentWord = strtoupper($isCreator ? $session->word_for_creator : $session->word_for_opponent);

        $guessedLettersKey = $isCreator ? 'guessed_letters_creator' : 'guessed_letters_opponent';
        $mistakesKey = $isCreator ? 'mistakes_creator' : 'mistakes_opponent';

        $guessedLetters = json_decode($session->$guessedLettersKey, true) ?? [];
        $mistakes = $session->$mistakesKey;

        if (strpos($currentWord, $letter) !== false) {
            $isCorrect = true;
            if (!in_array($letter, $guessedLetters)) {
                $guessedLetters[] = $letter; // Adăugăm  literele corecte
            }
        } else {
            if (!in_array($letter, $guessedLetters)) {
                $guessedLetters[] = $letter; // Adăugăm literele greșite
            }
            $mistakes++;
        }

        $guessedLetters = array_unique($guessedLetters);

        $session->$guessedLettersKey = json_encode($guessedLetters);
        $session->$mistakesKey = $mistakes;
        $session->save();

        $allLettersGuessed = $this->areAllLettersGuessed($currentWord, $guessedLetters);

        $finished = $allLettersGuessed || $mistakes >= ceil(strlen($currentWord) / 2);

        if ($finished) {
            $this->handleRoundCompletion($session, $isCreator, $allLettersGuessed);

            // Transmitem evenimentul către clienți cu valori goale pentru a se actualiza keyboard ul oponentului
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
        // Verificăm dacă runda curentă s-a terminat
        if ($allLettersGuessed || $session->mistakes_creator >= ceil(strlen($session->word_for_creator) / 2) || $session->mistakes_opponent >= ceil(strlen($session->word_for_opponent) / 2)) {
            // Dacă este rândul creatorului și ghicește corect sau face prea multe greșeli
            if ($isCreator) {
                // Schimbăm turul la oponent
                $session->turn = $session->opponent_id;
            } else {
                // Dacă este rândul oponentului, jocul se încheie
                $session->completed = true;
            }
            if ($session->completed) {
                // Calculate scores
                $maxMistakes = max(
                    ceil(strlen($session->word_for_creator) / 2),
                    ceil(strlen($session->word_for_opponent) / 2)
                );

                $scores = $this->calculateScores(
                    $session->mistakes_creator,
                    $session->mistakes_opponent,
                    $maxMistakes
                );
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
                $this->badgeService->hangmanGameBadges($creator);
                $this->badgeService->hangmanGameBadges($opponent);
                $this->userScoreService->awardPointsBasedOnHangmanScore($opponent, $scores['opponent']);
            }

            $session->save();
        }
    }
    /**
     * Calculate the scores for both players based on their mistakes.
     *
     * @param int $mistakesCreator Number of mistakes made by the creator.
     * @param int $mistakesOpponent Number of mistakes made by the opponent.
     * @param int $maxMistakes Maximum mistakes allowed based on word length.
     * @return array Returns an associative array with the scores for both players.
     */
    private function calculateScores(int $mistakesCreator, int $mistakesOpponent, int $maxMistakes): array
    {
        $pointsPerMistake = 100 / $maxMistakes;

        $creatorScore = 100 - ($mistakesCreator * $pointsPerMistake);
        $opponentScore = 100 - ($mistakesOpponent * $pointsPerMistake);

        return [
            'creator' => max(0, round($creatorScore)), // Ensure the score is not below 0
            'opponent' => max(0, round($opponentScore)) // Ensure the score is not below 0
        ];
    }

    public function getWordOptions()
    {
        // În AWS S3 ai un JSON cu cuvinte și hint-uri
        $wordData = Storage::disk('s3')->get('hangman/word_options.json');
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