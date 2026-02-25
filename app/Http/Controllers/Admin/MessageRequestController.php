<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MessageRequest;
use Illuminate\Http\Request;

class MessageRequestController extends Controller
{
    /**
     * Display a listing of all connection requests.
     */
    public function index()
    {
        // Eager load sender (Seeker) and receiver (Coach) using UUID relationships
        $requests = MessageRequest::with(['sender', 'receiver'])
            ->latest()
            ->paginate(15);

        return view('admin.interactions.requests', compact('requests'));
    }

    /**
     * Remove the specified request log.
     */
    public function destroy($id)
    {
        // Use destroy for UUID char(36) strings
        MessageRequest::destroy($id);

        return back()->with('success', 'Connection log entry deleted successfully.');
    }
}