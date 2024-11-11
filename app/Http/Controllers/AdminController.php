<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;

class AdminController extends BaseRoleController
{
    protected $dashboardService;

    function __construct(DashboardService $service)
    {
        parent::__construct('Admin');
        $this->dashboardService = $service;
    }

    protected function getDashboardData()
    {
        return $this->dashboardService->getAdminDashboardData();
    }
}
