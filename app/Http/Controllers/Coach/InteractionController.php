<?php
namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\InteractionRepositoryInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NewMessageNotification;

class InteractionController extends Controller
{
    public function __construct(
        protected InteractionRepositoryInterface $interactionRepo
    ) {}

    /**
     * List all active conversations (Interactions) for the coach.
     */
    public function index()
    {
        $interactions = $this->interactionRepo->getForCoach(Auth::id());
        return view('coach.interactions.index', compact('interactions'));
    }

    /**
     * The Chat Interface between Coach and a specific Seeker.
     */
    public function chat($seekerId)
    {
        $coachId = Auth::id();
        $seeker = User::findOrFail($seekerId);

        $messages = $this->interactionRepo->getConversation($seekerId, $coachId);
        $this->interactionRepo->markAsRead($coachId, $seekerId);

        // Mark database notifications as read for this specific conversation
        auth()->user()->unreadNotifications()
            ->where('data->type', 'chat_message')
            ->where('data->sender_id', $seekerId)
            ->get()
            ->markAsRead();

        return view('coach.interactions.chat', compact('seeker', 'messages'));
    }

    public function fetchMessages($seekerId)
    {
        $coachId = Auth::id();
        $messages = $this->interactionRepo->getConversation($seekerId, $coachId);
        
        // We return a view that ONLY contains the @foreach loop of messages
        return view('coach.interactions._messages', compact('messages'))->render();
    }

    /**
     * Send a reply to the seeker.
     */
    public function store(Request $request)
    {
        $request->validate([
            'seeker_id' => 'required|exists:users,id',
            'message' => 'required|string'
        ]);

        $interaction = $this->interactionRepo->create([
            'seeker_id' => $request->seeker_id,
            'coach_id'  => Auth::id(), // From Auth session
            'subject'   => 'Coach Reply',
            'message'   => $request->message,
            'status'    => 'sent' // Interaction status
        ]);

        // Send Notification to Seeker
        $seeker = User::find($request->seeker_id);
        $seeker->notify(new NewMessageNotification($interaction));

        return back()->with('success', 'Message sent.');
    }

    public function destroy($id)
    {
        $interaction = $this->interactionRepo->findById($id);
        if (!$interaction || $interaction->coach_id !== Auth::id()) {
            abort(403);
        }
        $this->interactionRepo->delete($id);
        return back()->with('success', 'Message deleted.');
    }
}