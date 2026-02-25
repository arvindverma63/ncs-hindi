<?php

namespace App\Repositories\Eloquent;

use App\Models\Blog;
use App\Models\BlogComment;
use App\Repositories\Contracts\CoachDashboardRepositoryInterface;
use Illuminate\Support\Facades\DB;

class EloquentCoachDashboardRepository implements CoachDashboardRepositoryInterface
{
    public function getStats(string $coachId): array
    {
        return [
            'total_blogs' => Blog::where('user_id', $coachId)->count(),
            'total_views' => Blog::where('user_id', $coachId)->sum('view_count'),
            'published_posts' => Blog::where('user_id', $coachId)->where('is_published', true)->count(),
            'pending_comments' => BlogComment::whereHas('blog', function($query) use ($coachId) {
                $query->where('user_id', $coachId);
            })->where('status', 'pending')->count(),
        ];
    }

    public function getRecentComments(string $coachId, int $limit = 5)
    {
        return BlogComment::whereHas('blog', function($query) use ($coachId) {
                $query->where('user_id', $coachId);
            })
            ->with(['blog', 'user'])
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getViewStats(string $coachId)
    {
        return Blog::where('user_id', $coachId)
            ->select('title', 'view_count')
            ->orderBy('view_count', 'desc')
            ->limit(10)
            ->get();
    }
}