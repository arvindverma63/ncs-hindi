<?php

namespace App\Repositories\Contracts;

interface CoachDashboardRepositoryInterface
{
    /**
     * Get statistics for the coach (total blogs, total views, etc.)
     */
    public function getStats(string $coachId): array;

    /**
     * Get the latest blog comments for this coach's articles.
     */
    public function getRecentComments(string $coachId, int $limit = 5);

    /**
     * Get view count trends for the coach's blogs.
     */
    public function getViewStats(string $coachId);
}