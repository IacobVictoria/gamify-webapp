<?php

namespace App\Http\Controllers;

use App\Events\GameEnded;
use App\Events\GameReady;
use App\Events\GameStarted;
use App\Events\GameUpdated;
use App\Events\OpponentJoined;
use App\Models\HangmanSession;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class HangmanGameController extends Controller
{
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
        $hint = $request->input('hint');

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
                $guessedLetters[] = $letter; // Adăugăm doar literele corecte
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

        // Verificăm dacă toate literele au fost ghicite
        $allLettersGuessed = $this->areAllLettersGuessed($currentWord, $guessedLetters);

        if ($allLettersGuessed || $mistakes >= ceil(strlen($currentWord) / 2)) {
            $this->handleRoundCompletion($session, $isCreator, $allLettersGuessed);
        }

        // Pregătim literele corecte și greșite pentru jucătorul curent
        $correctLetters = $this->getCorrectLetters($currentWord, $guessedLetters);
        $wrongLetters = $this->getWrongLetters($currentWord, $guessedLetters);

        // Transmitem evenimentul către clienți
        broadcast(new GameUpdated(
            $session->id,
            $session->turn,
            $correctLetters,
            $wrongLetters,
            $guessedLetters,
            $session->mistakes_creator,
            $session->mistakes_opponent
        ))->toOthers();

        return response()->json([
            'correct' => $isCorrect,
            'finished' => $allLettersGuessed || $mistakes >= ceil(strlen($currentWord) / 2),
            'nextTurn' => $session->turn,
            'errors' => $mistakes,
        ]);
    }

    private function getCorrectLetters(string $word, array $guessedLetters): array
    {
        $correctLetters = [];
        foreach ($guessedLetters as $letter) {
            // Verificăm dacă litera există în cuvânt
            if (strpos($word, $letter) !== false) { // `!== false` e corect
                $correctLetters[] = $letter;
            }
        }
        return $correctLetters;
    }


    /**
     * Verifică dacă toate literele au fost ghicite.
     */
    private function areAllLettersGuessed(string $word, array $guessedLetters): bool
    {
        foreach (str_split($word) as $char) {
            if (ctype_alpha($char) && !in_array($char, $guessedLetters)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Determină literele greșite ghicite de jucător.
     */
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

    /**
     * Gestionează finalizarea rundei.
     */
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

            // Resetăm literele și greșelile doar dacă jocul nu s-a terminat
            if (!$session->completed) {
                $session->guessed_letters_creator = json_encode([]);
                $session->guessed_letters_opponent = json_encode([]);
                $session->mistakes_creator = 0;
                $session->mistakes_opponent = 0;
            }

            // Salvăm starea sesiunii
            $session->save();

            // Declanșăm evenimentul `GameEnded` dacă jocul s-a încheiat
            if ($session->completed) {
                broadcast(new GameEnded($session->id));
            }
        }
    }


}