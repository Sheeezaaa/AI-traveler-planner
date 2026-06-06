<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TripPlannerController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\CompanionController;
use App\Http\Controllers\EmergencyController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\BookingController;
use App\Models\Destination;

// 1. Home / Landing Page
Route::get('/', function () {
    $destinations = Destination::take(3)->get();
    return view('home', compact('destinations'));
})->name('home');

// 2. Custom Authentication
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 3. Social Login (Google, Facebook, Twitter)
Route::get('/auth/{provider}',          [AuthController::class, 'redirectToProvider'])->name('social.redirect');
Route::get('/auth/{provider}/callback', [AuthController::class, 'handleProviderCallback'])->name('social.callback');

// 4. User Dashboard (Authenticated)
Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard')->middleware('auth');

// 5. Destinations Explorer
Route::get('/destinations', [DestinationController::class, 'index'])->name('destinations.index');
Route::get('/destinations/{id}', [DestinationController::class, 'show'])->name('destinations.show');

// 6. AI Trip Planner Core
Route::get('/plan', [TripPlannerController::class, 'showForm'])->name('planner.form');
Route::post('/plan', [TripPlannerController::class, 'planTrip'])->name('planner.plan');
Route::get('/trips/{id}', [TripPlannerController::class, 'showTrip'])->name('planner.show');
Route::post('/trips/{id}/save', [TripPlannerController::class, 'saveGuestTrip'])->name('planner.saveGuest');

// 7. Travel Companion Finder
Route::get('/companions', [CompanionController::class, 'index'])->name('companions.index');
Route::post('/companions', [CompanionController::class, 'store'])->name('companions.store')->middleware('auth');
Route::delete('/companions/{id}', [CompanionController::class, 'destroy'])->name('companions.destroy')->middleware('auth');

// 8. Emergency Info API
Route::get('/emergency/{destinationId}', [EmergencyController::class, 'getEmergencyData'])->name('emergency.data');

// 9. AI Chatbot API
Route::post('/chat', [ChatbotController::class, 'chat'])->name('chatbot.chat');

// 10. Hotel Bookings
Route::post('/hotels/{id}/book', [BookingController::class, 'store'])->name('hotels.book')->middleware('auth');

// 11. Test Route for GROQ API Key
Route::get('/test-groq', function () {
    return env('GROQ_API_KEY');
});