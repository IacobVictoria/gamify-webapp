<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AdminHangmanManagerController extends Controller
{
    private $s3FilePath = 'hangman/word_options_aws.json';
    public function index()
    {
        if (!Storage::disk('s3')->exists($this->s3FilePath)) {
            return response()->json([]);
        }

        $wordsJson = Storage::disk('s3')->get($this->s3FilePath);
        $words = json_decode($wordsJson, true);

        return Inertia::render('Admin/HangmanManager/Index', [
            'words' => $words
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'word' => 'required|string',
            'hint' => 'required|string',
        ]);

        $wordsJson = Storage::disk('s3')->exists($this->s3FilePath)
            ? Storage::disk('s3')->get($this->s3FilePath)
            : '[]';

        $words = json_decode($wordsJson, true);
        $words[] = ['word' => $request->word, 'hint' => $request->hint];

        Storage::disk('s3')->put($this->s3FilePath, json_encode($words, JSON_PRETTY_PRINT));

        return redirect()->back();
    }

    public function update(Request $request, $word)
    {
        $request->validate([
            'new_word' => 'required|string',
            'hint' => 'required|string',
        ]);

        $wordsJson = Storage::disk('s3')->get($this->s3FilePath);
        $words = json_decode($wordsJson, true);

        foreach ($words as &$item) {
            // Cu &$item, modifici direct structura originală a array-ului
            if ($item['word'] === $word) {
                $item['word'] = $request->new_word;
                $item['hint'] = $request->hint;
            }
        }

        Storage::disk('s3')->put($this->s3FilePath, json_encode($words, JSON_PRETTY_PRINT));

        return redirect()->back();
    }

    public function destroy($word)
    {
        $wordsJson = Storage::disk('s3')->get($this->s3FilePath);
        $words = json_decode($wordsJson, true);

        $words = array_filter($words, fn($item) => $item['word'] !== $word);

        Storage::disk('s3')->put($this->s3FilePath, json_encode(array_values($words), JSON_PRETTY_PRINT));

        return redirect()->back();
    }
}
