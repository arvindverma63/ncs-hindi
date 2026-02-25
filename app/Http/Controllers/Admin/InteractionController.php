<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\InteractionRepositoryInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InteractionController extends Controller
{
    public function __construct(
        protected InteractionRepositoryInterface $interactionRepo
    ) {}

    /**
     * Show a form to message a specific user.
     */
    public function createDirectMessage($userId)
    {
        $targetUser = User::findOrFail($userId);
        return view('admin.interactions.direct_message', compact('targetUser'));
    }

    /**
     * Send the message directly.
     */
    public function storeDirectMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'subject'     => 'required|string|max:255',
            'message'     => 'required|string',
        ]);

        // Logic: Admin acts as the 'sender'. 
        // We use the existing Interaction table structure.
        $this->interactionRepo->create([
            'seeker_id' => Auth::id(), // Admin is the sender
            'coach_id'  => $request->receiver_id,
            'subject'   => $request->subject,
            'message'   => $request->message,
            'status'    => 'sent'
        ]);

        return redirect()->route('admin.requests.index')->with('success', 'Message sent directly.');
    }
}