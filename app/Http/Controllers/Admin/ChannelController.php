<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommunityChannel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChannelController extends Controller
{
    /**
     * Display a listing of the channels for the admin.
     */
    public function index()
    {
        $channels = CommunityChannel::orderBy('sort_order', 'asc')->get();
        return view('admin.community.channels.index', compact('channels'));
    }

    /**
     * Store a newly created channel.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:community_channels,name',
            'description' => 'nullable|string',
            'icon'        => 'required|string', // e.g., fa-comments
            'sort_order'  => 'required|integer',
        ]);

        CommunityChannel::create([
            'id'          => (string) Str::uuid(),
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'description' => $request->description,
            'icon'        => $request->icon,
            'sort_order'  => $request->sort_order,
            'is_private'  => $request->has('is_private'),
        ]);

        return redirect()->back()->with('success', 'New Studio Room created successfully.');
    }

    /**
     * Update the specified channel.
     */
    public function update(Request $request, $id)
    {
        $channel = CommunityChannel::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:community_channels,name,' . $id,
        ]);

        $channel->update([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'description' => $request->description,
            'icon'        => $request->icon,
            'sort_order'  => $request->sort_order,
            'is_private'  => $request->has('is_private'),
        ]);

        return redirect()->back()->with('success', 'Channel updated.');
    }

    /**
     * Remove the channel and its messages.
     */
    public function destroy($id)
    {
        $channel = CommunityChannel::findOrFail($id);
        $channel->delete();

        return redirect()->back()->with('success', 'Channel deleted.');
    }
}
