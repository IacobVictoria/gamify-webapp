<?php

namespace App\Http\Controllers;

use App\Http\Requests\BadgeRequest;
use App\Models\Badge;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminBadgeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->input('filters', []);
        $badgeQuery = Badge::query();

        if (isset($filters['searchName'])) {

            $badgeQuery->where('name', 'like', '%' . $filters['searchName'] . '%');
        }
        $badges = $badgeQuery->paginate(10)->through(function ($badge) {
            return [
                'id' => $badge->id,
                'name' => $badge->name,
                'score' => $badge->score,
                'created_at' => $badge->created_at->format('Y-m-d'),
                'extra' => [
                    'description' => $badge->description,
                ]

            ];
        });

        return Inertia::render('Admin/Badges/List', [
            'badges' => $badges,
            'prevFilters' => $filters
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Badges/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BadgeRequest $request)
    {
        $validatedData = $request->validated();

        $badge = new Badge();
        $badge->id = Uuid::uuid();
        $badge->name = $validatedData['name'];
        $badge->score = $validatedData['score'];
        $badge->description = $validatedData['description'];
        $badge->save();

        return redirect()->route('admin.badges.index')->with('success', 'Badge created successfully!');


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $badgeId)
    {
        $badge = Badge::find($badgeId);

        return Inertia::render('Admin/Badges/Edit', [
            'badge' => $badge,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BadgeRequest $request, string $badgeId)
    {
        $badge = Badge::find($badgeId);

        $validatedData = $request->validated();

        $badge->name = $validatedData['name'];
        $badge->score = $validatedData['score'];
        $badge->description = $validatedData['description'];
        $badge->save();

        return redirect()->route('admin.badges.index')->with('success', 'Badge updated successfully!');
        ;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $badgeId)
    {
        $badge = Badge::find($badgeId);

        $badge->delete();

        return redirect()->route('admin.badges.index')->with('success', 'Badge deleted successfully!');
    }
}
