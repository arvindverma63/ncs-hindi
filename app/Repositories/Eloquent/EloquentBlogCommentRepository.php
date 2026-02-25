<?php

namespace App\Repositories\Eloquent;

use App\Models\BlogComment;
use App\Repositories\Contracts\BlogCommentRepositoryInterface;

class EloquentBlogCommentRepository implements BlogCommentRepositoryInterface
{
    protected $model;

    public function __construct(BlogComment)
    {
        $this->model = new BlogComment();
    }

    public function getAll(array $filters = [])
    {
        $query = $this->model->with(['blog', 'user']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['blog_id'])) {
            $query->where('blog_id', $filters['blog_id']);
        }

        return $query->latest()->paginate(15);
    }

    public function findById(string $id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function updateStatus(string $id, string $status)
    {
        $comment = $this->findById($id);
        return $comment->update(['status' => $status]);
    }

    public function delete(string $id)
    {
        return $this->model->destroy($id);
    }

    public function getCommentsByBlog(string $blogId)
    {
        return $this->model->where('blog_id', $blogId)
            ->whereNull('parent_id')
            ->where('status', 'approved')
            ->with(['user', 'replies.user'])
            ->oldest()
            ->get();
    }
}