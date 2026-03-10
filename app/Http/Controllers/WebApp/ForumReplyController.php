<?php

namespace App\Http\Controllers\WebApp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ForumReply;
use App\Models\ForumThread;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ForumReplyController extends Controller
{
    public function store(Request $request, $threadId)
    {
        if (!Auth::check()) {
            return redirect()->back()->with('error', 'Please login to join the discussion.');
        }

        $request->validate([
            'content' => 'required|string|min:1',
        ]);

        try {
            // Verify the thread exists (CHAR 36 UUID)
            $thread = ForumThread::findOrFail($threadId);

            ForumReply::create([
                'user_id'   => Auth::id(), // CHAR(36)
                'thread_id' => $thread->id, // CHAR(36)
                'content'   => $request->content, // Rich HTML from Quill
            ]);

            return redirect()->back()->with('success', 'Your reply has been posted.');
        } catch (\Exception $e) {
            Log::error("Failed to post reply: " . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function update(Request $request, $id)
    {
        $reply = ForumReply::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        // Check if the reply is older than 24 hours
        if ($reply->created_at->diffInHours(Carbon::now()) >= 24) {
            return response()->json(['error' => 'Edit time (24h) has expired.'], 403);
        }

        $request->validate(['content' => 'required|string']);

        $reply->update(['content' => $request->content]);

        return response()->json(['success' => 'Reply updated successfully.']);
    }

    public function destroy($id)
    {
        $reply = ForumReply::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        // Check if the reply is older than 24 hours
        if ($reply->created_at->diffInHours(Carbon::now()) >= 24) {
            return redirect()->back()->with('error', 'Deletion time (24h) has expired.');
        }

        $reply->delete();

        return redirect()->back()->with('success', 'Reply deleted.');
    }
}
