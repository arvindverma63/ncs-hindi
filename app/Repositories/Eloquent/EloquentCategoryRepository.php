<?php

namespace App\Repositories\Eloquent;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class EloquentCategoryRepository implements CategoryRepositoryInterface
{

    protected $model;

    public function __construct(Category $model)
    {
        $this->model = $model;
    }
    public function getAll(): Collection
    {
        return Category::orderBy('name')->get();
    }

    public function findById(string $id): ?Category
    {
        return Category::find($id);
    }

    public function findBySlug(string $slug): ?Category
    {
        return Category::where('slug', $slug)->first();
    }

    public function create(array $data): Category
    {
        // Auto-generate slug if not present
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        return Category::create($data);
    }

    public function update(string $id, array $data): bool
    {
        $category = Category::find($id);
        if (!$category) return false;

        if (isset($data['name']) && empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        return $category->update($data);
    }

    public function delete(string $id): bool
    {
        return Category::destroy($id);
    }
    public function getTopPopular($limit = 5)
    {
        return $this->model->withCount('coaches')
            ->orderByDesc('coaches_count')
            ->take($limit)
            ->get();
    }
}