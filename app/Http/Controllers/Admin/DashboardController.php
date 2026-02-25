<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\CoachRepositoryInterface;
use App\Repositories\Contracts\SeekerRepositoryInterface;
use App\Repositories\Contracts\CategoryRepositoryInterface;

class DashboardController extends Controller
{
    protected $coachRepo;
    protected $seekerRepo;
    protected $categoryRepo;

    public function __construct(
        CoachRepositoryInterface $coachRepo,
        SeekerRepositoryInterface $seekerRepo,
        CategoryRepositoryInterface $categoryRepo
    ) {
        $this->coachRepo = $coachRepo;
        $this->seekerRepo = $seekerRepo;
        $this->categoryRepo = $categoryRepo;
    }

    public function index()
    {
        // 1. Widget Stats
        $stats = [
            'verified_coaches' => $this->coachRepo->countApproved(),
            'pending_coaches'  => $this->coachRepo->countPending(),
            'total_seekers'    => $this->seekerRepo->countAll(),
            'active_inquiries' => 0, 
        ];

        $recentRequests = $this->coachRepo->getRecentPending(5);

        $topCategories = $this->categoryRepo->getTopPopular(5);

        $chartData = [
            'labels' => $topCategories->pluck('name')->toArray(),
            'series' => $topCategories->pluck('coaches_count')->toArray(),
        ];

        return view('dashboard', compact('stats', 'recentRequests', 'chartData'));
    }
}