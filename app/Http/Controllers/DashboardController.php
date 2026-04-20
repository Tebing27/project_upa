<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return request()->user()?->can('admin')
            ? view('admin-dashboard')
            : view('dashboard');
    }
}
