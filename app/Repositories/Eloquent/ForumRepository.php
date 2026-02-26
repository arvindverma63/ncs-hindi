<?php

namespace App\Repositories\Eloquent;

use App\Models\ForumThread;
use App\Models\ForumReply;
use App\Repositories\Contracts\ForumRepositoryInterface;
use Illuminate\Support\Str;

class ForumRepository implements ForumRepositoryInterface
{
    public function getAllThreads()
    {
        return ForumThread::with(['author', 'category'])
            ->latest()
            ->get();
    }

    public function getTrendingThreads($limit = 3)
    {
        return ForumThread::with(['author'])
            ->orderBy('view_count', 'desc')
            ->take($limit)
            ->get();
    }

    public function findThreadBySlug($slug)
    {
        return ForumThread::with(['author', 'category', 'stems'])
            ->where('slug', $slug)
            ->firstOrFail();
    }

    public function getRepliesByThreadId($threadId)
    {
        return ForumReply::with('user')
            ->where('thread_id', $threadId)
            ->whereNull('parent_id')
            ->oldest()
            ->get();
    }
    public function storeThread(array $data)
    {
        return ForumThread::create([
            'user_id'     => auth()->id(),
            'category_id' => $data['category_id'],
            'title'       => $data['title'],
            'slug'        => Str::slug($data['title']) . '-' . time(),
            'content'     => $data['content'],
            'is_verified' => auth()->user()->user_type === 0 ? 1 : 0, // Admin posts are verified
        ]);
    }

    public function findThreadById($id)
    {
        return \App\Models\ForumThread::with(['author', 'category', 'replies.user'])
            ->findOrFail($id);
    }
}
