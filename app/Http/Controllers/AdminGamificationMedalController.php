<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminStoreMedalRequest;
use App\Http\Requests\AdminUpdateMedalRequest;
use App\Models\Medal;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminGamificationMedalController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->input('filters', []);
        $orderBy = $request->input('orderBy', 'created_at');
        $orderDirection = $request->input('orderDirection', 'desc');

        $medalsQuery = Medal::query();

        if (isset($filters['searchTier'])) {
            $medalsQuery->where('tier', 'like', '%' . $filters['searchTier'] . '%');
        }

        if (in_array($orderBy, ['threshold', 'created_at', 'discount'])) {
            $orderDirection = in_array($orderDirection, ['asc', 'desc']) ? $orderDirection : 'asc';
            $medalsQuery->orderBy($orderBy, $orderDirection);
        }

        $medals = $medalsQuery->paginate(10)->through(function ($medal) {
            return [
                'id' => $medal->id,
                'tier' => $medal->tier,
                'discount' => $medal->discount,
                'threshold' => $medal->threshold,
                'created_at' => $medal->created_at->format('Y-m-d'),
            ];
        });

        return Inertia::render('Admin/Medals/Index', [
            'medals' => $medals,
            'prevFilters' => $filters,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Medals/Create');
    }

    public function store(AdminStoreMedalRequest $request)
    {
        Medal::create(array_merge(
            $request->validated(),
            ['id' => Uuid::uuid()]
        ));

        return redirect()->route('admin-gamification.medals.index')
            ->with('message', 'Medal created successfully.');
    }


    public function edit($medalId)
    {
        $medal = Medal::findOrFail($medalId);

        return Inertia::render('Admin/Medals/Edit', [
            'medal' => $medal,
        ]);
    }

    public function update(AdminUpdateMedalRequest $request, $medalId)
    {
        $medal = Medal::findOrFail($medalId);
        $medal->update($request->validated());

        return redirect()->route('admin-gamification.medals.index')
            ->with('message', 'Medal updated successfully.');
    }

    public function destroy($medalId)
    {
        $medal = Medal::findOrFail($medalId);
        $medal->delete();

        return redirect()->route('admin-gamification.medals.index')
            ->with('message', 'Medal deleted successfully.');
    }
}
