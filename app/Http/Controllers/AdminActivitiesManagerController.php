<?php

namespace App\Http\Controllers;

use App\Enums\ActivityType;
use App\Http\Requests\ActivityStoreRequest;
use App\Http\Requests\ActivityUpdateRequest;
use App\Models\Activity;
use App\Models\Product;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminActivitiesManagerController extends Controller
{
    public function index(Request $request)
    {
        $types = collect(ActivityType::cases())->map(fn($type) => [
            'value' => $type->value,
            'label' => $type->label(),
        ]);

        $filters = $request->input('filters', []);
        $orderBy = $request->input('orderBy', 'created_at');
        $orderDirection = $request->input('orderDirection', 'desc');

        $activitiesQuery = Activity::query();

        if (isset($filters['searchTitle'])) {
            $activitiesQuery->where('title', 'like', '%' . $filters['searchTitle'] . '%');
        }

        if (isset($filters['searchPublished']) && in_array($filters['searchPublished'], ['true', 'false'])) {
            $activitiesQuery->where('is_published', $filters['searchPublished'] === 'true');
        }

        if (!empty($filters['searchType'])) {
            $activitiesQuery->where('type', $filters['searchType']);
        }

        if (in_array($orderBy, ['score', 'created_at'])) {
            $orderDirection = in_array($orderDirection, ['asc', 'desc']) ? $orderDirection : 'asc';
            $activitiesQuery->orderBy($orderBy, $orderDirection);
        }

        $activities = $activitiesQuery->paginate(10)->through(function ($activity) {
            return [
                'id' => $activity->id,
                'title' => $activity->title,
                'score' => $activity->score,
                'type' => $activity->type,
                'created_at' => $activity->created_at->format('Y-m-d'),
                'is_published' => $activity->is_published,
            ];
        });

        return Inertia::render('Admin/ActivitiesManager/Index', [
            'activities' => $activities,
            'types' => $types,
            'prevFilters' => $filters,
        ]);
    }

    public function create()
    {
        $products = Product::all();
        return Inertia::render('Admin/ActivitiesManager/Create', [
            'products' => $products
        ]);
    }

    public function edit($activityId)
    {
        $activity = Activity::findOrFail($activityId);
        $products = Product::all();

        return Inertia::render('Admin/ActivitiesManager/Edit', [
            'activity' => $activity,
            'products' => $products
        ]);
    }

    public function store(ActivityStoreRequest $request)
    {
        $data = $request->validated();

        $activity = Activity::create([
            'id' => Uuid::uuid(),
            'title' => $data['title'],
            'type' => $data['type'],
            'score' => $data['score'] ?? null,
            'is_published' => $data['is_published'] ?? false,
            'description' => $data['description'],
            'details' => $data['details'],
        ]);

        return redirect()
            ->route('admin-gamification.activities.index')
            ->with('message', 'Activity created successfully!');

    }

    public function update(ActivityUpdateRequest $request, string $activityId)
    {
        $activity = Activity::findOrFail($activityId);

        $activity->update([
            'title' => $request->input('title'),
            'type' => $request->input('type'),
            'score' => $request->input('score'),
            'is_published' => $request->boolean('is_published'),
            'description' => $request->input('description'),
            'details' => $request->input('details'),
        ]);

        return redirect()->route('admin-gamification.activities.index')
            ->with('message', 'Activity updated successfully.');
    }

    public function destroy(string $activityId)
    {
        $activity = Activity::findOrFail($activityId);
        $activity->delete();

        return redirect()->route('admin-gamification.activities.index')
            ->with('message', 'Activity deleted successfully.');
    }
}
