<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\SeekerDashboardInterface;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(
        protected SeekerDashboardInterface $dashboardRepo
    ) {}

    public function index()
    {
        $userId = Auth::id();

        // Get data through the repository
        $stats = $this->dashboardRepo->getStats($userId);
        $recentRequests = $this->dashboardRepo->getRecentRequests($userId);

        return view('seeker.dashboard', compact('stats', 'recentRequests'));
    }
}