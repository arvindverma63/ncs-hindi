<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MessageRequest;
use App\Repositories\Contracts\InteractionRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NewMessageNotification;

class MessagingController extends Controller
{
    public function __construct(
        protected InteractionRepositoryInterface $interactionRepo
    ) {}

    public function index($coachId)
    {
        $seekerId = Auth::id();

        $isConnected = MessageRequest::where('sender_id', $seekerId)
            ->where('receiver_id', $coachId)
            ->where('status', 'accepted')
            ->exists();

        if (!$isConnected) {
            return redirect()->route('seeker.coaches.index')
                ->with('error', 'You must have an accepted connection request to message this coach.');
        }

        $coach = User::findOrFail($coachId);
        $messages = $this->interactionRepo->getConversation($seekerId, $coachId);

        auth()->user()->unreadNotifications()
            ->where('data->type', 'chat_message')
            ->where('data->sender_id', $coachId)
            ->get()
            ->markAsRead();

        return view('seeker.messaging.chat', compact('coach', 'messages'));
    }

    public function fetchMessages($coachId) {
        $messages = $this->interactionRepo->getConversation(Auth::id(), $coachId);
        return view('seeker.messaging._messages', compact('messages'))->render();
    }

    public function sendMessage(Request $request, $coachId)
    {
        $request->validate(['message' => 'required|string']); //

        $interaction = $this->interactionRepo->create([
            'seeker_id' => Auth::id(),
            'coach_id'  => $coachId,
            'subject'   => 'Direct Message',
            'message'   => $request->message,
            'status'    => 'sent'
        ]);

        $coach = User::find($coachId);
        if ($coach) {
            $coach->notify(new NewMessageNotification($interaction));
        }

        return back()->with('success', 'Message sent.');
    }
}