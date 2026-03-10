<?php

namespace App\Http\Controllers\WebApp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Like;
use App\Models\ForumThread;
use App\Models\MusicStem;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggle(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Authentication required'], 401);
        }

        $request->validate([
            'id'   => 'required|string|size:36',
            'type' => 'required|string|in:thread,stem'
        ]);

        $userId = Auth::id(); // This is a CHAR(36) UUID per your user table
        $targetId = $request->id;

        $map = [
            'thread' => ForumThread::class,
            'stem'   => MusicStem::class,
        ];

        $modelClass = $map[$request->type];

        $like = Like::where('user_id', $userId)
                    ->where('likeable_id', $targetId)
                    ->where('likeable_type', $modelClass)
                    ->first();

        if ($like) {
            $like->delete();
            $status = 'unliked';
        } else {
            Like::create([
                'user_id'       => $userId,
                'likeable_id'   => $targetId,
                'likeable_type' => $modelClass
            ]);
            $status = 'liked';
        }

        $totalLikes = Like::where('likeable_id', $targetId)
                         ->where('likeable_type', $modelClass)
                         ->count();

        return response()->json([
            'status' => $status,
            'count'  => number_format($totalLikes)
        ]);
    }
}
