<?php

namespace App\Http\Controllers\WebApp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CommunityMessage;
use App\Models\CommunityChannel;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CommunityMessageController extends Controller
{
    /**
     * Get the last 50 messages for a specific channel.
     */

    public function showChat($channelId = null)
    {
        // 1. Fetch all available channels
        $channels = CommunityChannel::orderBy('sort_order', 'asc')->get();

        // 2. Identify the active channel with a fallback
        $activeChannel = $channelId
            ? CommunityChannel::find($channelId)
            : $channels->first();

        // 3. Handle the "Invalid ID" fallback
        if (!$activeChannel && $channels->isNotEmpty()) {
            $activeChannel = $channels->first();
        }

        // 4. Conditional Message Loading
        // We only attempt to load messages if an active channel actually exists
        $messages = collect(); // Default to an empty collection

        if ($activeChannel) {
            $messages = CommunityMessage::where('channel_id', $activeChannel->id)
                ->with('user:id,name,profile_image') // Optimized to only select needed columns
                ->whereNull('parent_id')
                ->whereNull('deleted_at') // Explicitly exclude soft-deleted messages
                ->latest()
                ->limit(50)
                ->get()
                ->reverse();
        }

        // 5. Return the view
        // The view now handles the case where $activeChannel or $messages is empty
        return view('webapp.community.chat', compact('channels', 'activeChannel', 'messages'));
    }

    public function index($channelId)
    {
        $messages = CommunityMessage::where('channel_id', $channelId)
            ->with(['user:id,name,profile_image', 'replies.user'])
            ->whereNull('parent_id') // Get main thread messages
            ->whereNull('deleted_at') // Explicitly exclude soft-deleted messages
            ->latest()
            ->paginate(50);

        return response()->json($messages);
    }

    /**
     * Store and Broadcast a new message.
     */
    public function store(Request $request)
    {
        $request->validate([
            'channel_id' => 'required|string|size:36',
            'message'    => 'nullable|string',
            'parent_id'  => 'nullable|string|size:36',
            'file'       => 'nullable|file|max:10240', // 10MB limit
        ]);

        // At least message or file must be provided
        if (!$request->filled('message') && !$request->hasFile('file')) {
            return response()->json(['error' => 'Message or file is required'], 422);
        }

        $type = 'text';
        $metadata = [];

        // Handle File Uploads (Stems, Images, Audio)
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('community/attachments', 'public');
            $type = $this->determineMessageType($file);

            $metadata = [
                'file_path' => Storage::url($path),
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
            ];
        }

        $message = CommunityMessage::create([
            'user_id'    => Auth::id(),
            'channel_id' => $request->channel_id,
            'parent_id'  => $request->parent_id,
            'message'    => $request->message ?? '',
            'type'       => $type,
            'metadata'   => $metadata,
        ]);

        // Load relations for the WebSocket payload
        $message->load('user:id,name,profile_image');

        // Broadcast to WebSockets (Laravel Reverb/Pusher)
        broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'status'  => 'sent',
            'message' => $message
        ]);
    }

    /**
     * Helper to determine if message is an image, audio, or general file.
     */
    private function determineMessageType($file)
    {
        $mime = $file->getMimeType();
        if (str_contains($mime, 'image')) return 'image';
        if (str_contains($mime, 'audio')) return 'audio';
        return 'attachment';
    }

    /**
     * Delete a message (Soft Delete).
     */
    public function destroy($id)
    {
        $message = CommunityMessage::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $message->delete();

        return response()->json([
            'status' => 'deleted',
            'message_id' => $id
        ]);
    }
}
