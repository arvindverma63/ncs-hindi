<?php

namespace App\Repositories\Contracts;

interface BlogCommentRepositoryInterface
{
    public function getAll(array $filters = []);
    public function findById(string $id);
    public function create(array $data);
    public function updateStatus(string $id, string $status);
    public function delete(string $id);
    public function getCommentsByBlog(string $blogId);
}