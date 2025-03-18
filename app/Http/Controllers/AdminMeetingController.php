<?php

namespace App\Http\Controllers;

use App\Enums\MeetingPeriod;
use App\Http\Requests\AdminMeetingStoreRequest;
use App\Http\Requests\AdminMeetingUpdateRequest;
use App\Models\Meeting;
use App\Models\ReportCategory;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminMeetingController extends Controller
{
    public function index()
    {
        $meetings = Meeting::all()->each(function ($meeting) {
            $meeting->categories = ReportCategory::whereIn('id', $meeting->report_category_ids)->pluck('name');
        });

        $categories = ReportCategory::all();

        $periods = MeetingPeriod::values();

        return Inertia::render('Admin/Meetings/Index', [
            'meetings' => $meetings,
            'categories' => $categories,
            'periods' => $periods
        ]);
    }

    public function store(AdminMeetingStoreRequest $request)
    {
        Meeting::create([
            'id' => Uuid::uuid(),
            'title' => $request->title,
            'description' => $request->description,
            'start' => $request->start,
            'end' => $request->start,
            'period' => MeetingPeriod::from($request->period),
            'report_category_ids' => $request->report_category_ids,
        ]);

        return redirect()->route('admin.meetings.index')->with('success', 'Meeting creat cu succes!');
    }

    public function update(AdminMeetingUpdateRequest $request, $id)
    {
        $meeting = Meeting::findOrFail($id);

        $meeting->update([
            'title' => $request->title,
            'description' => $request->description,
            'start' => $request->start,
            'end' => $request->start, 
            'period' => MeetingPeriod::from($request->period),
            'report_category_ids' => $request->report_category_ids,
        ]);

        return redirect()->route('admin.meetings.index')->with('success', 'Meeting actualizat cu succes!');
    }
}
