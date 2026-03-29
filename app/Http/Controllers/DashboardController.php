<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Lấy vai trò (Spatie)
        $role = $user->getRoleNames()->first() ?? 'Chưa có vai trò';
        
        // Lấy danh sách quyền (Spatie)
        $permissions = $user->getAllPermissions()->pluck('name');

        return view('welcome-auth', compact('user', 'role', 'permissions'));
    }
}