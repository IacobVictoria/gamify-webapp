<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class NutritionAndWellnessController extends Controller
{
    public function index()
    {
        return Inertia::render('User/FitnessAndWellness/Index');
    }

    public function countCalories(Request $request)
    {
        $validated = $request->validate([
            'sex' => 'required|in:male,female',
            'age' => 'required|integer|min:1|max:120',
            'height' => 'required|numeric|min:1',
            'heightUnit' => 'required|in:cm,ft/in',
            'weight' => 'required|numeric|min:1',
            'weightUnit' => 'required|in:kg,lb',
            'activity' => 'required|in:lightly_active,moderately_active,active,very_active',
        ]);

        if ($validated['heightUnit'] === 'ft/in') {
            $validated['height'] = $this->convertFeetToCm($validated['height']);
        }
        if ($validated['weightUnit'] === 'lb') {
            $validated['weight'] = $this->convertLbToKg($validated['weight']);
        }
        $bmr = $this->calculateBMR($validated);
        $calorieIntake = $this->adjustCaloriesForActivity($bmr, $validated['activity']);

        return response()->json([
            'min' => $calorieIntake - 250,
            'max' => $calorieIntake + 250,
        ]);

    }
    private function convertFeetToCm($feet)
    {
        return $feet * 30.48;
    }

    private function convertLbToKg($pounds)
    {
        return $pounds * 0.453592;
    }

    private function calculateBMR($data)
    {
        // calcul BMR folosind formula Harris-Benedict
        if ($data['sex'] === 'male') {
            return 88.36 + (13.4 * $data['weight']) + (4.8 * $data['height']) - (5.7 * $data['age']);
        } else {
            return 447.6 + (9.2 * $data['weight']) + (3.1 * $data['height']) - (4.3 * $data['age']);
        }
    }

    private function adjustCaloriesForActivity($bmr, $activity)
    {
        $factors = [
            'lightly_active' => 1.375,
            'moderately_active' => 1.55,
            'active' => 1.725,
            'very_active' => 1.9,
        ];

        return round($bmr * $factors[$activity]);
    }

}
