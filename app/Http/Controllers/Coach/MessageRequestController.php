<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\MessageRequestInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageRequestController extends Controller
{
    public function __construct(protected MessageRequestInterface $requestRepo) {}

    public function index()
    {
        $requests = $this->requestRepo->getPendingForUser(Auth::id());
        return view('coach.interactions.requests', compact('requests'));
    }

    public function update($id, $status)
    {
        if (!in_array($status, ['accepted', 'rejected'])) {
            abort(400);
        }

        $request = $this->requestRepo->findById($id);

        // Security check: Ensure coach owns this request
        if (!$request || $request->receiver_id !== Auth::id()) {
            abort(403);
        }

        $this->requestRepo->updateStatus($id, $status);

        $msg = ($status === 'accepted') ? 'Connection accepted! You can now message this seeker.' : 'Connection declined.';
        return back()->with('success', $msg);
    }
}