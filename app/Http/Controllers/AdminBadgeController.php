<?php

namespace App\Http\Controllers;

use App\Enums\BadgeCategory;
use App\Http\Requests\AdminStoreBadgeRequest;
use App\Http\Requests\AdminUpdateBadgeRequest;
use App\Http\Requests\BadgeRequest;
use App\Models\Badge;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AdminBadgeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->input('filters', []);
        $orderBy = $request->input('orderBy', 'created_at');
        $orderDirection = $request->input('orderDirection', 'desc');

        $badgeQuery = Badge::query();

        if (isset($filters['searchName'])) {

            $badgeQuery->where('name', 'like', '%' . $filters['searchName'] . '%');
        }

        if (in_array($orderBy, ['name', 'price', 'score', 'created_at'])) {
            $orderDirection = in_array($orderDirection, ['asc', 'desc']) ? $orderDirection : 'asc';
            $badgeQuery->orderBy($orderBy, $orderDirection);
        }

        if (!empty($filters['searchCategory'])) {
            $badgeQuery->where('category', $filters['searchCategory']);
        }

        $badges = $badgeQuery->paginate(10)->through(function ($badge) {
            return [
                'id' => $badge->id,
                'name' => $badge->name,
                'score' => $badge->score,
                'category' => $badge->category,
                'created_at' => $badge->created_at->format('Y-m-d'),
                'extra' => [
                    'description' => $badge->description,
                ]

            ];
        });

        $categories = collect(BadgeCategory::cases())->map(fn($case) => [
            'value' => $case->value,
            'label' => $case->label(),
        ]);

        return Inertia::render('Admin/Badges/List', [
            'badges' => $badges,
            'prevFilters' => $filters,
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = BadgeCategory::cases();

        $categories = array_map(function ($category) {
            return [
                'value' => $category->value,
                'label' => ucfirst(str_replace('_', ' ', $category->value)) // Convertim enum-ul în format prietenos (ex. 'reviewer' => 'Reviewer')
            ];
        }, $categories);

        return Inertia::render('Admin/Badges/Create', [
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdminStoreBadgeRequest $request)
    {
        $validatedData = $request->validated();

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('badges_images', 's3');

            $imageUrl = Storage::disk('s3')->url($imagePath);
        }
        $badge = new Badge();
        $badge->id = Uuid::uuid();
        $badge->name = $validatedData['name'];
        $badge->score = $validatedData['score'];
        $badge->description = $validatedData['description'];
        $badge->category = $validatedData['category'];
        $badge->image_path = $imageUrl;
        $badge->save();
        return redirect()->route('admin-gamification.badges.index')->with('success', 'Badge created successfully!');

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
        $categories = BadgeCategory::cases();

        $categories = array_map(function ($category) {
            return [
                'value' => $category->value,
                'label' => ucfirst(str_replace('_', ' ', $category->value)) // Convertim enum-ul în format prietenos (ex. 'reviewer' => 'Reviewer')
            ];
        }, $categories);


        return Inertia::render('Admin/Badges/Edit', [
            'badge' => $badge,
            'categories' => $categories
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdminUpdateBadgeRequest $request, string $badgeId)
    {
        $badge = Badge::find($badgeId);

        $validatedData = $request->validated();

        $badge->name = $validatedData['name'];
        $badge->score = $validatedData['score'];
        $badge->description = $validatedData['description'];
        $badge->category = $validatedData['category'];
        if ($request->hasFile('image')) {
            // Șterge imaginea veche de pe S3
            if ($badge->image_path) {
                // Extrage calea fișierului din URL
                $parsedUrl = parse_url($badge->image_path);
                $imagePath = ltrim($parsedUrl['path'], '/');
                Storage::disk('s3')->delete($imagePath);
            }

            // Salvează imaginea noua
            $imagePath = $request->file('image')->store('badges_images', 's3');
            $badge->image_path = Storage::disk('s3')->url($imagePath);
        }
        $badge->save();

        return redirect()->route('admin-gamification.badges.index')->with('success', 'Badge updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $badgeId)
    {
        $badge = Badge::find($badgeId);
        if ($badge->image_path) {
            // Extrage calea fișierului din URL
            $parsedUrl = parse_url($badge->image_path);
            $imagePath = ltrim($parsedUrl['path'], '/');

            Storage::disk('s3')->delete($imagePath);
        }
        $badge->delete();

        return redirect()->route('admin-gamification.badges.index')->with('success', 'Badge deleted successfully!');
    }
}
