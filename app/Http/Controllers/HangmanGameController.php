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

        $letter = $request->input('letter');
        $isCorrect = false;

        $currentWord = ($user->id === $session->creator_id)
            ? $session->word_for_creator
            : $session->word_for_opponent;

        $guessedLettersKey = ($user->id === $session->creator_id)
            ? 'guessed_letters_creator'
            : 'guessed_letters_opponent';

        $mistakesKey = ($user->id === $session->creator_id)
            ? 'mistakes_creator'
            : 'mistakes_opponent';

        $guessedLetters = json_decode($session->$guessedLettersKey, true) ?? [];
        $mistakes = $session->$mistakesKey;

        if (strpos($currentWord, $letter) !== false) {
            $isCorrect = true;
            $guessedLetters[] = $letter;

            $allLettersGuessed = true;
            foreach (str_split($currentWord) as $char) {
                if (!in_array($char, $guessedLetters)) {
                    $allLettersGuessed = false;
                    break;
                }
            }

            if ($allLettersGuessed) {
                $session->$guessedLettersKey = json_encode($guessedLetters);
                $session->save();

                if ($session->turn === $session->creator_id) {
                    $session->completed = true; // Marchează sesiunea ca finalizată
                    $session->save();
                    broadcast(new GameEnded($session->id));
                    return response()->json(['message' => 'Game Ended']);
                }

                broadcast(new GameUpdated(
                    $session->id,
                    $session->turn,
                    json_decode($session->guessed_letters_creator, true) ?? [],
                    json_decode($session->guessed_letters_opponent, true) ?? [],
                    array_unique(array_merge(
                        json_decode($session->guessed_letters_creator, true) ?? [],
                        json_decode($session->guessed_letters_opponent, true) ?? []
                    )),
                    $session->mistakes_creator,
                    $session->mistakes_opponents
                ))->toOthers();

                return response()->json([
                    'correct' => $isCorrect,
                    'finished' => true,
                    'nextTurn' => $session->turn,
                ]);
            }
        } else {
            $mistakes++;
            $session->$mistakesKey = $mistakes;

            if ($mistakes >= ceil(strlen($currentWord) / 2)) {
                $session->turn = ($user->id === $session->creator_id)
                    ? $session->opponent_id
                    : $session->creator_id;

                $session->save();

                if ($session->turn === $session->creator_id) {
                    $session->completed = true; // Marchează sesiunea ca finalizată
                    $session->save();
                    broadcast(new GameEnded($session->id));
                    return response()->json(['message' => 'Game Ended']);
                }


                broadcast(new GameUpdated(
                    $session->id,
                    $session->turn,
                    json_decode($session->guessed_letters_creator, true) ?? [],
                    json_decode($session->guessed_letters_opponent, true) ?? [],
                    array_unique(array_merge(
                        json_decode($session->guessed_letters_creator, true) ?? [],
                        json_decode($session->guessed_letters_opponent, true) ?? []
                    )),
                    $session->mistakes_creator,
                    $session->mistakes_opponent
                ))->toOthers();

                return response()->json([
                    'correct' => $isCorrect,
                    'finished' => true,
                    'nextTurn' => $session->turn,
                ]);
            }
        }

        $session->$guessedLettersKey = json_encode($guessedLetters);
        $session->save();

        broadcast(new GameUpdated(
            $session->id,
            $session->turn,
            json_decode($session->guessed_letters_creator, true) ?? [],
            json_decode($session->guessed_letters_opponent, true) ?? [],
            array_unique(array_merge(
                json_decode($session->guessed_letters_creator, true) ?? [],
                json_decode($session->guessed_letters_opponent, true) ?? []
            )),
            $session->mistakes_creator,
            $session->mistakes_opponent
        ))->toOthers();

        return response()->json([
            'correct' => $isCorrect,
            'finished' => false,
            'nextTurn' => $session->turn,
        ]);
    }

}
