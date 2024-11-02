<?php

namespace App\Http\Controllers;

use App\Models\UserQuiz;
use App\Models\UserQuizRemark;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminUserQuizRemarkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $quizId)
    {
        $filters = $request->input('filters', []);

        $quizRemarksQuery = UserQuizRemark::query()->with('user')->where('quiz_id', $quizId);

        if (isset($filters['searchName'])) {
            $quizRemarksQuery->whereHas('user', function ($query) use ($filters) {
                $query->where('name', 'like', '%' . $filters['searchName'] . '%');
            });
        }

        $remarks = $quizRemarksQuery->paginate(10)->through(function ($remark) {
            return [
                'description' => $remark->description,
                'user_name' => $remark->user->name,
                'created_at' => $remark->created_at->format('Y-m-d')
            ];
        });

        return Inertia::render('Admin/UserQuizzes/QuizRemarks', [
            'remarks' => $remarks,
            'quizId' => $quizId,
            'prevFilters' => $filters,
        ]);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
