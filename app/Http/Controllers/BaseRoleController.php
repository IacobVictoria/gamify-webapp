<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

abstract class BaseRoleController extends Controller
{
    protected $role;

    public function __construct(string $role)
    {
        $this->role = $role;
    }

    public function dashboard()
    {
        return Inertia::render($this->role . '/Dashboard', $this->getDashboardData());
    }

    abstract protected function getDashboardData();
}
