<?php

namespace App\Repositories\Eloquent;

use App\Models\Blog;
use App\Repositories\Contracts\CoachBlogRepositoryInterface;
use Illuminate\Support\Str;

class EloquentCoachBlogRepository implements CoachBlogRepositoryInterface
{
    protected $model;

    public function __construct(Blog $blog)
    {
        $this->model = $blog;
    }

    public function getCoachBlogs(string $coachId)
    {
        return $this->model->where('user_id', $coachId)
            ->with('category')
            ->latest()
            ->paginate(10);
    }

    public function findCoachBlog(string $coachId, string $blogId)
    {
        return $this->model->where('user_id', $coachId)->findOrFail($blogId);
    }

    public function store(array $data)
    {
        $data['slug'] = Str::slug($data['title']) . '-' . rand(1000, 9999);
        $data['is_published'] = $data['is_published'] ?? false;
        
        return $this->model->create($data);
    }

    public function update(string $blogId, array $data, string $coachId)
    {
        $blog = $this->findCoachBlog($coachId, $blogId);
        
        if (isset($data['title'])) {
            $data['slug'] = Str::slug($data['title']) . '-' . substr($blogId, 0, 5);
        }

        $blog->update($data);
        return $blog;
    }

    public function delete(string $blogId, string $coachId)
    {
        $blog = $this->findCoachBlog($coachId, $blogId);
        return $blog->delete();
    }
}