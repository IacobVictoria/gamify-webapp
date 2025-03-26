<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;

class SuperAdminController extends BaseRoleController
{
    protected $dashboardService;

    function __construct(DashboardService $service)
    {
        parent::__construct('SuperAdmin');
        $this->dashboardService = $service;
    }

    protected function getDashboardData()
    {
        return $this->dashboardService->getSuperAdminDashboardData();
    }
    //
}
