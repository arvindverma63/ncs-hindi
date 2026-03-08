<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\CoachRepositoryInterface;
use App\Repositories\Contracts\SeekerRepositoryInterface;
use App\Repositories\Contracts\CategoryRepositoryInterface;

class DashboardController extends Controller
{


    public function index()
    {
        return view('dashboard');
    }
}
