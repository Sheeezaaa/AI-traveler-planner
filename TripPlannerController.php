<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Trip;
use App\Models\Hotel;
use App\Services\TravelPlannerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TripPlannerController extends Controller
{
    protected TravelPlannerService $plannerService;

    public function __construct(TravelPlannerService $plannerService)
    {
        $this->plannerService = $plannerService;
    }

    public function showForm()
    {
        $destinations = Destination::all();
        return view('planner.form', compact('destinations'));
    }

    public function planTrip(Request $request)
    {
        $request->validate([
            'destination_id' => 'required|exists:destinations,id',
            'budget'         => 'required|numeric|min:1000',
            'days'           => 'required|integer|min:1|max:30',
            'travel_type'    => 'required|string|in:solo,family,friends,honeymoon',
        ]);

        $destination = Destination::findOrFail($request->destination_id);
        $userId = Auth::id();

        $trip = $this->plannerService->generatePlan(
            $destination,
            (float) $request->budget,
            (int)   $request->days,
            $request->travel_type,
            $userId
        );

        return redirect()->route('planner.show', $trip->id)
            ->with('success', 'AI has generated your personalized itinerary!');
    }

    public function showTrip($id)
    {
        $trip = Trip::with(['destination', 'itinerary'])->findOrFail($id);

        // Hotels for this destination (for booking form)
        $hotels = Hotel::where('destination_id', $trip->destination_id)
            ->orderBy('price_per_night', 'asc')
            ->get();

        // Travel tips
        $travelTips = $this->plannerService->getTravelTips($trip->travel_type);

        // ✅ Weather data (same logic as DestinationController)
        $weatherData = $this->getWeatherData($trip->destination->name);

        return view('planner.show', compact('trip', 'hotels', 'travelTips', 'weatherData'));
    }

    public function saveGuestTrip(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('info', 'Please login to save your trip!');
        }

        $trip = Trip::findOrFail($id);
        if (!$trip->user_id) {
            $trip->user_id = Auth::id();
            $trip->save();
            return redirect()->route('dashboard')
                ->with('success', 'Trip saved to your profile!');
        }

        return redirect()->route('dashboard')->with('info', 'Trip already saved.');
    }

    // ── Weather helper (simulated — no API key needed) ──────────────────────
    private function getWeatherData(string $name): array
    {
        $n = strtolower($name);

        if (str_contains($n, 'hunza'))  return ['temp'=>18,'condition'=>'Sunny & Clear','humidity'=>'35%','wind'=>'8 km/h',  'icon'=>'fa-sun text-yellow-500'];
        if (str_contains($n, 'skardu')) return ['temp'=>15,'condition'=>'Breezy & Cool','humidity'=>'30%','wind'=>'15 km/h', 'icon'=>'fa-cloud-sun text-sky-400'];
        if (str_contains($n, 'murree')) return ['temp'=>20,'condition'=>'Foggy & Cool', 'humidity'=>'70%','wind'=>'10 km/h', 'icon'=>'fa-smog text-slate-400'];
        if (str_contains($n, 'swat'))   return ['temp'=>24,'condition'=>'Mostly Sunny', 'humidity'=>'40%','wind'=>'9 km/h',  'icon'=>'fa-sun text-yellow-400'];
        if (str_contains($n, 'naran'))  return ['temp'=>16,'condition'=>'Light Drizzle','humidity'=>'65%','wind'=>'14 km/h', 'icon'=>'fa-cloud-showers-heavy text-blue-400'];

        return ['temp'=>20,'condition'=>'Partly Cloudy','humidity'=>'50%','wind'=>'12 km/h','icon'=>'fa-cloud-sun text-slate-400'];
    }
}