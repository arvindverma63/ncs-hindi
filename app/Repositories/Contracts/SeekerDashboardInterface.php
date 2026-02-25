<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;

interface SeekerDashboardInterface
{
    /**
     * Get aggregated statistics for the seeker dashboard.
     */
    public function getStats(string $userId): array;

    /**
     * Get the most recent connection requests sent by the seeker.
     */
    public function getRecentRequests(string $userId, int $limit = 5): Collection;
}