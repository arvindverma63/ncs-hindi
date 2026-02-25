<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\CoachDashboardRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $dashboardRepo;

    public function __construct(CoachDashboardRepositoryInterface $dashboardRepo)
    {
        $this->dashboardRepo = $dashboardRepo;
    }

    public function index()
    {
        $coachId = Auth::id();
        
        $stats = $this->dashboardRepo->getStats($coachId);
        $recentComments = $this->dashboardRepo->getRecentComments($coachId);
        $topBlogs = $this->dashboardRepo->getViewStats($coachId);

        return view('coach.dashboard', compact('stats', 'recentComments', 'topBlogs'));
    }
}