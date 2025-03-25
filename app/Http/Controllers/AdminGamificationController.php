<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;

class AdminGamificationController extends BaseRoleController
{
    protected $dashboardService;

    function __construct(DashboardService $service)
    {
        parent::__construct('AdminGamification');
        $this->dashboardService = $service;
    }

    protected function getDashboardData()
    {
        return $this->dashboardService->getAdminGamificationDashboardData();
    }
}
