<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminReportGeneratorsController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Reports_Interactive/Index');
    }
}
