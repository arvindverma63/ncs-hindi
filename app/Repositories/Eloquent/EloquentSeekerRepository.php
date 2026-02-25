<?php

namespace App\Repositories\Eloquent;

use App\Models\SeekerProfile;
use App\Repositories\Contracts\SeekerRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentSeekerRepository implements SeekerRepositoryInterface
{
    protected $model;

    public function __construct(SeekerProfile $model)
    {
        $this->model = $model;
    }

    public function getAll(int $perPage = 10): LengthAwarePaginator
    {
        $query = $this->model->with(['user' => function ($q) {
            $q->withTrashed();
        }]);

        // Filter by Search (Name or Email in the related User table)
        if (request()->filled('search')) {
            $search = request('search');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        // Filter by Verification Status
        if (request()->filled('status')) {
            $status = request('status') === 'verified' ? 1 : 0;
            $query->where('is_verified', $status);
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }

    public function findById(string $id): ?SeekerProfile
    {
        // Also include withTrashed here so 'Edit/Show' pages don't crash
        return $this->model->with(['user' => function ($query) {
            $query->withTrashed();
        }])->find($id);
    }

    public function findByUserId(string $userId): ?SeekerProfile
    {
        return $this->model->where('user_id', $userId)->first();
    }

    public function create(array $data): SeekerProfile
    {
        return $this->model->create($data);
    }

    public function update(string $id, array $data): bool
    {
        $profile = $this->model->find($id);
        
        if (!$profile) {
            return false;
        }

        return $profile->update($data);
    }

    public function delete(string $id): bool
    {
        $profile = $this->model->find($id);
        
        if ($profile) {
            return $profile->delete();
        }
        
        return false;
    }

    public function countAll()
    {
        return $this->model->count();
    }
}