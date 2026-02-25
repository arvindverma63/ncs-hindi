<?php

namespace App\Repositories\Eloquent;

use App\Models\CoachProfile;
use App\Repositories\Contracts\CoachRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentCoachRepository implements CoachRepositoryInterface
{
    protected $model;

    // 1. THIS CONSTRUCTOR IS REQUIRED FOR $this->model TO WORK
    public function __construct(CoachProfile $model)
    {
        $this->model = $model;
    }

    public function getAll(int $perPage = 10): LengthAwarePaginator
    {
        $query = $this->model->with(['user' => function ($q) {
            $q->withTrashed();
        }, 'categories']);

        // Search by User Name, User Email, or Coach Company
        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
                })->orWhere('company_name', 'LIKE', "%{$search}%");
            });
        }

        // Filter by Approval Status
        if (request()->filled('status')) {
            $query->where('approval_status', request('status'));
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }

    public function getPending(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->where('approval_status', 'pending')
            ->with(['user' => function ($query) {
                $query->withTrashed();
            }])
            ->latest()
            ->paginate($perPage);
    }

    public function findById(string $id): ?CoachProfile
    {
        return $this->model->with(['user' => function ($query) {
            $query->withTrashed();
        }, 'categories'])->find($id);
    }

    public function findByUserId(string $userId): ?CoachProfile
    {
        return $this->model->where('user_id', $userId)->first();
    }

    public function create(array $data): CoachProfile
    {
        $profile = $this->model->create($data);
        
        // Sync Categories if provided
        if (isset($data['categories'])) {
            $profile->categories()->sync($data['categories']);
        }

        return $profile;
    }

    public function update(string $id, array $data): bool
    {
        $profile = $this->model->find($id);
        
        if (!$profile) {
            return false;
        }

        if (isset($data['categories'])) {
            $profile->categories()->sync($data['categories']);
        }

        return $profile->update($data);
    }

    public function updateStatus(string $id, string $status): bool
    {
        $profile = $this->model->find($id);
        if ($profile) {
            return $profile->update(['approval_status' => $status]);
        }
        return false;
    }

    public function delete(string $id): bool
    {
        $profile = $this->model->find($id);
        if ($profile) {
            return $profile->delete();
        }
        return false;
    }

    public function countApproved()
    {
        return $this->model->where('approval_status', 'approved')->count();
    }

    public function countPending()
    {
        return $this->model->where('approval_status', 'pending')->count();
    }

    public function getRecentPending($limit = 5)
    {
        return $this->model->with(['user' => function ($query) {
                $query->withTrashed();
            }, 'categories'])
            ->where('approval_status', 'pending')
            ->latest()
            ->take($limit)
            ->get();
    }
}