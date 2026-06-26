<?php

// app/Http/Controllers/DashboardController.php
namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function admin()
    {
        return view('admin.dashboard');
    }

    public function teamleader()
    {
        return view('tl.dashboard');
    }

    public function telecaller()
    {
        return view('telecaller.dashboard');
    }
}