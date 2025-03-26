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

            // 4. Analiza răspunsului AI pentru produse relevante
            $suggestedProducts = $this->getSuggestedProducts($userMessage);

            // 5. Returnăm răspunsul cu sugestiile de produse
            return response()->json([
                'response' => $responseContent,
                'products' => $suggestedProducts,
            ]);
        } catch (\Exception $e) {
            logger()->error('OpenAI Error: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
    private function getSuggestedProducts(string $userMessage)
    {
        $categories = Product::distinct()->pluck('category')->toArray();

        $matchingKeywords = array_filter($categories, function ($category) use ($userMessage) {
            return stripos($userMessage, $category) !== false;
        });

        if (empty($matchingKeywords)) {
            return [];
        }

        $products = Product::where(function ($query) use ($matchingKeywords) {
            foreach ($matchingKeywords as $keyword) {
                $query->orWhere('category', 'LIKE', "%$keyword%");
            }
        })->take(5)->get();

        return $products;
    }

}
