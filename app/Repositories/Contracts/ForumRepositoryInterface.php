<?php

namespace App\Repositories\Contracts;

interface ForumRepositoryInterface
{
    public function getAllThreads();
    public function getTrendingThreads($limit = 3);
    public function findThreadBySlug($slug);
    public function getRepliesByThreadId($threadId);
    public function storeThread(array $data); // New Method
    public function findThreadById($id);
}
