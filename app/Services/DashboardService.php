<?php

namespace App\Services;
use App\Models\Badge;
use App\Models\Permission;
use App\Models\Product;
use App\Models\QrCodeScan;
use App\Models\Role;
use App\Models\Survey;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use function Aws\map;

class DashboardService
{
    protected $npsService;

    public function __construct(NpsService $npsService)
    {
        $this->npsService = $npsService;
    }

    public function getUserDashboardData()
    {
        $user = Auth::user();
        $userScore = $user->score;
        $yourPositionInTop = User::where('score', '>', $userScore)->count() + 1;

        return [
            'account' => [
                'name' => $user->name,
                'score' => $userScore,
                'nr_badges' => $user->badges()->count(),
                'position_leaderboard' => $yourPositionInTop,
                'gender' => $user->gender
            ]
        ];
    }

    public function getAdminDashboardData()
    {
        $survey = Survey::where('is_published', true)->first();

        // Verifică dacă există un survey publicat
        $nps = $survey ? $this->npsService->calculateNps($survey->id)['nps'] : null;
        $monthlyNpsData = $survey ? $this->npsService->calculateMonthlyNps($survey->id) : [];

        return [
            'accounts' => User::orderBy('created_at', 'desc')->take(5)->get(),
            'accountsNumber' => User::all()->count(),
            'roles' => Role::take(5)->get(),
            'rolesNumber' => Role::all()->count(),
            'permissions' => Permission::orderBy('id', 'desc')->take(5)->get(),
            'permissionsNumber' => Permission::all()->count(),
            'products' => Product::orderBy('created_at', 'desc')->take(5)->get(),
            'productsNumber' => Product::all()->count(),
            'badges' => Badge::all(),
            'badgesNumber' => Badge::all()->count(),
            'nps' => $nps,
            'monthlyNpsData' => $monthlyNpsData,
        ];
    }
}
