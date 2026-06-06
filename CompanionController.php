<?php

namespace App\Http\Controllers;

use App\Models\Companion;
use App\Models\Destination;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanionController extends Controller
{
    public function index()
    {
        // Require login to browse and match
        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'Please sign in to browse and connect with travel companions!');
        }

        $destinations = Destination::all();
        // Load companions with their user and destination models
        $companions = Companion::with(['user', 'destination'])
            ->where('status', 'active')
            ->where('user_id', '!=', Auth::id()) // don't show self in findings
            ->latest()
            ->get();

        // Load current user's active companion requests
        $myRequests = Companion::with('destination')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('companions.index', compact('companions', 'destinations', 'myRequests'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'destination_id' => 'required|exists:destinations,id',
            'bio' => 'required|string|min:10|max:500',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Check if there is an active trip for this user and destination to link
        $trip = Trip::where('user_id', Auth::id())
            ->where('destination_id', $request->destination_id)
            ->latest()
            ->first();

        Companion::create([
            'user_id' => Auth::id(),
            'destination_id' => $request->destination_id,
            'trip_id' => $trip ? $trip->id : null,
            'bio' => $request->bio,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'active'
        ]);

        return redirect()->route('companions.index')
            ->with('success', 'Your travel companion request has been posted successfully!');
    }

    public function destroy($id)
    {
        $companion = Companion::where('user_id', Auth::id())->findOrFail($id);
        $companion->delete();

        return redirect()->route('companions.index')
            ->with('success', 'Your companion request was removed.');
    }
}
