<?php

namespace App\Http\Controllers;

use App\Services\DashboardServices;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private DashboardServices $dashboardServices;

    public function __construct()
    {
        $this->dashboardServices = new DashboardServices();
        $this->middleware(['isAuth']);
    }

    public function index()
    {
        $counts = $this->dashboardServices->getCounts();

        $users = $counts['usersCount'];
        $roles = $counts['rolesCount'];
        $permissions = $counts['permissionsCount'];

        return view('main', compact('users', 'roles', 'permissions'));
    }
}
