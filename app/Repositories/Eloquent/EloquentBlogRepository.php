<?php

namespace App\Repositories\Eloquent;

use App\Models\Blog;
use App\Repositories\Contracts\BlogRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentBlogRepository implements BlogRepositoryInterface
{
    protected $model;

    public function __construct(Blog $model)
    {
        $this->model = $model;
    }

    public function getAll(int $perPage = 10): LengthAwarePaginator
    {
        $query = $this->model->with(['author', 'category']);

        if (request()->filled('search')) {
            $search = request('search');
            $query->where('title', 'LIKE', "%{$search}%");
        }

        if (request()->filled('status')) {
            $query->where('is_published', request('status'));
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }

    public function findById(string $id): ?Blog
    {
        return $this->model->with(['author', 'category'])->find($id);
    }

    public function findBySlug(string $slug): ?Blog
    {
        return $this->model->where('slug', $slug)->first();
    }

    public function create(array $data): Blog
    {
        return $this->model->create($data);
    }

    public function update(string $id, array $data): bool
    {
        $blog = $this->findById($id);
        return $blog ? $blog->update($data) : false;
    }

    public function delete(string $id): bool
    {
        $blog = $this->model->find($id);
        return $blog ? $blog->delete() : false;
    }

    public function updateStatus(string $id, bool $status): bool
    {
        $blog = $this->model->find($id);
        if ($blog) {
            return $blog->update(['is_published' => $status]);
        }
        return false;
    }
}