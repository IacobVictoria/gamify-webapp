<?php

namespace App\Http\Controllers;


use App\Models\Product;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;


class OpenAiController extends Controller
{
    public function respondMessages(Request $request)
    {
        // 1. Preluăm mesajul utilizatorului
        $userMessage = $request->input('message');

        // 2. Formăm structura pentru OpenAI
        $messages = [
            [
                'role' => 'system',
                'content' => 'You are an AI assistant. Help the user with their inquiries.',
            ],
            [
                'role' => 'user',
                'content' => $userMessage,
            ],
        ];

        // 3. Trimitem cererea către OpenAI
        try {
            $response = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => $messages,
            ]);

            $responseContent = $response['choices'][0]['message']['content'] ?? '';

            // 5. Returnăm răspunsul cu sugestiile de produse
            return response()->json([
                'response' => $responseContent,
            ]);
        } catch (\Exception $e) {
            logger()->error('OpenAI Error: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

}
