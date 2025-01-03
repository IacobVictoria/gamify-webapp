<?php

namespace App\Http\Controllers;

use App\Events\GameStarted;
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
    
        return response()->json(['message' => 'Word and hint saved successfully']);
    }

}
