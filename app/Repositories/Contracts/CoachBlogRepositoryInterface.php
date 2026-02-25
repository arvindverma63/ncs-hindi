<?php

namespace App\Repositories\Contracts;

interface CoachBlogRepositoryInterface
{
    public function getCoachBlogs(string $coachId);
    public function findCoachBlog(string $coachId, string $blogId);
    public function store(array $data);
    public function update(string $blogId, array $data, string $coachId);
    public function delete(string $blogId, string $coachId);
}