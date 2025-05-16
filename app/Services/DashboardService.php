<?php

namespace App\Services;

use App\Services\DashboardRolesType\AdminDashboardService;
use App\Services\DashboardRolesType\AdminGamificationDashboardService;
use App\Services\DashboardRolesType\SuperAdminDashboardService;
use App\Services\DashboardRolesType\UserDashboardService;

class DashboardService
{
    protected $adminDashboardService, $superAdminDashboardService, $userDashboard, $adminGamificationDashboardService;

    public function __construct(UserDashboardService $userDashboardService, AdminDashboardService $adminDashboardService, SuperAdminDashboardService $superAdminDashboardService, AdminGamificationDashboardService $adminGamificationDashboardService)
    {
        $this->userDashboard = $userDashboardService;
        $this->adminDashboardService = $adminDashboardService;
        $this->superAdminDashboardService = $superAdminDashboardService;
        $this->adminGamificationDashboardService = $adminGamificationDashboardService;
    }

    public function getUserDashboardData()
    {
        return $this->userDashboard->getDashboardData();
    }

    public function getAdminDashboardData()
    {
       return  $this->adminDashboardService->getDashboardData();
    }

    public function getSuperAdminDashboardData()
    {
       return $this->superAdminDashboardService->getDashboardData();
    }

    public function getAdminGamificationDashboardData()
    {
        return $this->adminGamificationDashboardService->getDashboardData();
    }

}
