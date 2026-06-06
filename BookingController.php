<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\HotelBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    /**
     * Handle the hotel room booking request.
     */
    public function store(Request $request, $hotelId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1|max:10',
        ]);

        $hotel = Hotel::findOrFail($hotelId);

        try {
            // Create the booking record
            HotelBooking::create([
                'hotel_id' => $hotel->id,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'check_in' => $request->check_in,
                'check_out' => $request->check_out,
                'guests' => $request->guests,
                'status' => 'confirmed', // Auto-confirming for this project
                'total_price' => $hotel->price_per_night * 1, // Simplified pricing for demo
            ]);

            return back()->with('success', "Success! Your luxury room at {$hotel->name} has been booked. We will contact you at {$request->phone} shortly.");
            
        } catch (\Exception $e) {
            Log::error('Booking Error: ' . $e->getMessage());
            return back()->with('error', 'Sorry, there was an error processing your booking. Please try again.');
        }
    }
}
