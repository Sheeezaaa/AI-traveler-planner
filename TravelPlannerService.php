<?php

namespace App\Services;

use App\Models\Destination;
use App\Models\Hotel;
use App\Models\Trip;
use App\Models\Itinerary;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TravelPlannerService
{
    /**
     * Generate complete trip plan, optimize budget, and save to database.
     */
    public function generatePlan(
        Destination $destination,
        float $budget,
        int $days,
        string $travelType,
        ?int $userId = null
    ): Trip {

        // 1. Determine Tier based on Budget per Day
        $dailyBudget = $budget / $days;

        if ($dailyBudget < 8000) {
            $tier = 'budget';
        } elseif ($dailyBudget < 20000) {
            $tier = 'standard';
        } else {
            $tier = 'luxury';
        }

        // 2. Select corresponding hotel for the destination
        $hotel = Hotel::where('destination_id', $destination->id)
            ->where('tier', $tier)
            ->first()
            ?? Hotel::where('destination_id', $destination->id)->first();

        $hotelCostPerNight = $hotel
            ? $hotel->price_per_night
            : ($tier === 'budget' ? 3000 : ($tier === 'standard' ? 8000 : 20000));

        $hotelNights    = max($days - 1, 1);
        $hotelTotalCost = $hotelCostPerNight * $hotelNights;

        // 3. Compute daily costs per tier
        [$transportDaily, $foodDaily, $activitiesDaily] = match ($tier) {
            'budget'   => [1500,  1500,  800],
            'standard' => [5000,  3000, 2500],
            'luxury'   => [14000, 6500, 7500],
        };

        $totalTransport  = $transportDaily  * $days;
        $totalFood       = $foodDaily       * $days;
        $totalActivities = $activitiesDaily * $days;
        $totalEstimated  = $hotelTotalCost + $totalTransport + $totalFood + $totalActivities;

        // 4. Smart Budget Optimization
        if ($totalEstimated > $budget && $tier !== 'budget') {
            $cheaperHotel = Hotel::where('destination_id', $destination->id)
                ->where('tier', 'budget')
                ->first();

            if ($cheaperHotel) {
                $hotel             = $cheaperHotel;
                $hotelCostPerNight = $cheaperHotel->price_per_night;
                $hotelTotalCost    = $hotelCostPerNight * $hotelNights;
            }

            $transportDaily  = 2000;
            $foodDaily       = 1800;
            $activitiesDaily = 1000;
            $totalTransport  = $transportDaily  * $days;
            $totalFood       = $foodDaily       * $days;
            $totalActivities = $activitiesDaily * $days;
            $totalEstimated  = $hotelTotalCost + $totalTransport + $totalFood + $totalActivities;
        }

        // 5. Generate AI-powered Day-by-Day Itinerary via GROQ
        $hotelName = $hotel ? $hotel->name : 'Local Guesthouse';
        $dayPlans  = $this->generateItineraryDays(
            $destination->name, $days, $tier, $hotelName, $travelType, $budget
        );

        // 6. Save Trip
        $trip = Trip::create([
            'user_id'        => $userId,
            'destination_id' => $destination->id,
            'budget'         => $budget,
            'days'           => $days,
            'travel_type'    => $travelType,
            'currency'       => 'PKR',
        ]);

        // 7. Save Itinerary
        Itinerary::create([
            'trip_id'              => $trip->id,
            'day_plans'            => $dayPlans,
            'estimated_transport'  => $totalTransport,
            'estimated_food'       => $totalFood,
            'estimated_activities' => $totalActivities,
            'estimated_hotels'     => $hotelTotalCost,
            'total_estimated'      => $totalEstimated,
        ]);

        return $trip;
    }

    // -------------------------------------------------------------------------
    // GROQ AI Itinerary Generator (falls back to hardcoded data if API fails)
    // -------------------------------------------------------------------------

    private function generateItineraryDays(
        string $destName,
        int $days,
        string $tier,
        string $hotelName,
        string $travelType,
        float $budget
    ): array {

        $apiKey = env('GROQ_API_KEY');

        if (!empty($apiKey)) {
            try {
                $prompt = "You are an expert Pakistan travel planner.\n\n"
                    . "Generate a detailed {$days}-day itinerary for a {$travelType} trip to {$destName}, Pakistan.\n"
                    . "Budget: PKR " . number_format($budget) . " ({$tier} tier). Hotel: {$hotelName}.\n\n"
                    . "RULES:\n"
                    . "1. Return ONLY a valid JSON array. No markdown, no backticks, no extra text.\n"
                    . "2. Each element MUST have exactly these keys: day (integer), title (string), morning (string), afternoon (string), evening (string), night (string).\n"
                    . "3. Make activities specific to {$destName} real landmarks, local food, and culture.\n"
                    . "4. Day 1 must be Arrival and Check-in. Last day must be Farewell and Return.\n"
                    . "5. Keep each time-slot to 1-2 sentences.\n\n"
                    . "Example format:\n"
                    . '[{"day":1,"title":"Arrival & Check-in","morning":"Depart early from your city.","afternoon":"Arrive and check-in at hotel.","evening":"Stroll around local bazaar.","night":"Traditional dinner at hotel."}]';

                $response = Http::timeout(45)
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $apiKey,
                        'Content-Type'  => 'application/json',
                    ])
                    ->post('https://api.groq.com/openai/v1/chat/completions', [
                        'model'    => 'llama3-8b-8192',
                        'messages' => [
                            [
                                'role'    => 'system',
                                'content' => 'You are a Pakistan travel expert. Always respond with ONLY a valid JSON array. No markdown. No explanation. No extra text whatsoever.',
                            ],
                            [
                                'role'    => 'user',
                                'content' => $prompt,
                            ],
                        ],
                        'temperature' => 0.7,
                        'max_tokens'  => 3000,
                    ]);

                if ($response->successful()) {
                    $raw = $response->json()['choices'][0]['message']['content'] ?? '';

                    // Strip markdown fences if model adds them
                    $clean = preg_replace('/```json\s*/i', '', $raw);
                    $clean = preg_replace('/```\s*/i', '',   $clean);
                    $clean = trim($clean);

                    $parsed = json_decode($clean, true);

                    if (
                        is_array($parsed)
                        && count($parsed) > 0
                        && isset($parsed[0]['day'], $parsed[0]['morning'])
                    ) {
                        return array_map(
                            fn($p) => array_merge($p, ['is_optimized' => ($tier === 'budget')]),
                            $parsed
                        );
                    }
                }

                Log::warning('TravelPlannerService: GROQ invalid response — using fallback.', [
                    'status' => $response->status(),
                    'body'   => substr($response->body(), 0, 500),
                ]);

            } catch (\Throwable $e) {
                Log::error('TravelPlannerService GROQ exception: ' . $e->getMessage());
            }
        }

        // Fallback to hardcoded itinerary if GROQ fails or key missing
        return $this->getFallbackItinerary($destName, $days, $tier, $hotelName);
    }

    // -------------------------------------------------------------------------
    // Fallback hardcoded itinerary
    // -------------------------------------------------------------------------

    private function getFallbackItinerary(
        string $destName,
        int $days,
        string $tier,
        string $hotelName
    ): array {
        $pool     = $this->getDestinationActivities($destName, $tier);
        $sights   = $pool['sights'] ?? ['Explore local scenic points', 'Experience local culture'];
        $dayPlans = [];

        for ($d = 1; $d <= $days; $d++) {
            if ($d === 1) {
                $dayPlans[] = [
                    'day'          => $d,
                    'title'        => 'Arrival & Check-in',
                    'morning'      => 'Depart from your city in the early morning and enjoy the scenic drive through the mountains.',
                    'afternoon'    => "Arrive at {$destName}. Check in at '{$hotelName}', freshen up and unpack.",
                    'evening'      => '05:00 PM — ' . ($pool['arrival'] ?? 'Light stroll around the local bazaar and photography of the scenic mountains.'),
                    'night'        => 'Traditional local dinner and trip briefing for the days ahead.',
                    'is_optimized' => ($tier === 'budget'),
                ];
            } elseif ($d === $days) {
                $dayPlans[] = [
                    'day'          => $d,
                    'title'        => 'Farewell & Return Drive',
                    'morning'      => 'Last-minute souvenir shopping — walnuts, dry apricots, and local handicrafts.',
                    'afternoon'    => "Check-out from '{$hotelName}' and enjoy a farewell lunch.",
                    'evening'      => 'Begin the return journey home with beautiful mountain memories.',
                    'night'        => 'Arrive safely at your home city.',
                    'is_optimized' => ($tier === 'budget'),
                ];
            } else {
                $idx1 = (($d - 2) * 2) % count($sights);
                $idx2 = (($d - 2) * 2 + 1) % count($sights);

                $dayPlans[] = [
                    'day'          => $d,
                    'title'        => 'Exploration & Sights',
                    'morning'      => '09:00 AM — Excursion: ' . $sights[$idx1] . '.',
                    'afternoon'    => '01:30 PM — Lunch at a local restaurant, then: ' . $sights[$idx2] . '.',
                    'evening'      => '06:00 PM — Sunset viewing, shopping for local spices, dry fruits, and woolens.',
                    'night'        => "08:30 PM — Relaxed dinner and overnight stay at {$hotelName}.",
                    'is_optimized' => ($tier === 'budget'),
                ];
            }
        }

        return $dayPlans;
    }

    // -------------------------------------------------------------------------
    // Curated activity pools per destination
    // -------------------------------------------------------------------------

    private function getDestinationActivities(string $destName, string $tier): array
    {
        $n = strtolower($destName);

        if (str_contains($n, 'hunza')) {
            return [
                'arrival' => 'Visit the historic Baltit Fort (700+ years old) and enjoy breathtaking viewpoints over Karimabad.',
                'sights'  => [
                    'Drive through the Attabad Tunnel to the stunning turquoise Attabad Lake and rent a boat to sail across the glacial waters.',
                    "Hike to Eagle's Nest viewpoint in Duiker for a golden-hour panorama over Ladyfinger and Rakaposhi peaks.",
                    'Visit Altit Fort and its award-winning Royal Kha Basi Gardens with ancient Tibetan-influenced architecture.',
                    'Cross the thrilling Passu Suspension Bridge, then enjoy tea with views of the iconic Passu Cones.',
                    'Take a scenic day drive to Khunjerab Pass (15,400 ft) — the Pakistan-China border — spotting Ibex along the way.',
                    'Explore the Hopper Glacier in Nagar Valley and walk along the active black glacier trails.',
                ],
            ];
        }

        if (str_contains($n, 'skardu')) {
            return [
                'arrival' => "Trek to the 16th-century Kharpocho Fort overlooking the Indus River and explore Skardu's old bazaar.",
                'sights'  => [
                    'Relax at the world-famous heart-shaped Shangrila Resort (Lower Kachura Lake) and try local fresh cherries.',
                    'Take an adventurous jeep ride to Upper Kachura Lake for local trout fishing and boating.',
                    'Excursion to Deosai National Park — the Land of Giants — visiting the crystal-clear Sheosar Lake.',
                    'Visit Serena Shigar Fort, a beautifully restored 400-year-old fort with tea served in its ancient orchards.',
                    'Stroll through the Cold Desert of Katpana and photograph sand dunes beneath snowy peaks.',
                    'Explore traditional organic farming villages and witness ancient ecological irrigation channels.',
                ],
            ];
        }

        if (str_contains($n, 'murree')) {
            return [
                'arrival' => "Stroll down Murree's famous colonial Mall Road, tasting street fries, walnuts, and hot tea in the cool breeze.",
                'sights'  => [
                    'Visit Pindi Point chairlift for a magnificent ride over the forest canopy and deep valleys.',
                    'Drive to Patriata (New Murree) for high-speed cable-cars over deep pine canyons.',
                    'Hike the peaceful Pipeline Track in Ayubia National Park, spotting local monkeys and colourful birds.',
                    'Visit the Bhurban resort, enjoy golf course views and coffee in green valleys.',
                    "Explore Nathiagali's wooden colonial houses and eat their famous fiery Patakha Chargha chicken.",
                    'Hike Mushkpuri Peak (9,400 ft) through lush meadows and alpine wildflowers.',
                ],
            ];
        }

        if (str_contains($n, 'swat')) {
            return [
                'arrival' => 'Visit the famous White Palace (Marghazar) and enjoy a riverside walk along the crystal-clear Swat River.',
                'sights'  => [
                    'Explore the ancient Buddhist ruins of Butkara Stupa — a 2,000-year-old archaeological treasure.',
                    "Drive to Malam Jabba — Pakistan's premier ski resort — for chairlift rides and mountain views.",
                    'Visit Kalam Valley for lush green meadows, the roaring Ushu Forest, and trout fishing.',
                    'Trek to the beautiful Mahudand Lake through alpine meadows and wildflowers.',
                    'Explore the green terraced fields and local fruit orchards of Upper Swat.',
                    'Visit the Fizagat Park riverside picnic spot and enjoy a peaceful afternoon by the water.',
                ],
            ];
        }

        if (str_contains($n, 'naran') || str_contains($n, 'kaghan')) {
            return [
                'arrival' => 'Arrive in Naran and take a gentle evening walk along the Kunhar River amidst towering pine forests.',
                'sights'  => [
                    'Jeep safari to the legendary Saif-ul-Malook Lake (10,578 ft) — one of the most beautiful lakes in Asia.',
                    'Drive to Babusar Top (13,691 ft) — the highest pass on the KKH — for jaw-dropping snowy panoramas.',
                    'Visit Lalazar Plateau, one of the most photographed alpine meadows in Pakistan, full of wildflowers.',
                    'Explore Batakundi Valley and the forests of Naran for bird watching and nature photography.',
                    "Hike to Ansoo Lake (the Tear Drop Lake) through scenic mountain trails.",
                    'Fish for local rainbow trout in the Kunhar River with a licensed fishing guide.',
                ],
            ];
        }

        // Generic fallback for other destinations
        return [
            'arrival' => 'Explore the local town center, visit the main bazaar, and enjoy a traditional welcome dinner.',
            'sights'  => [
                'Visit the main scenic viewpoints and capture stunning landscape photographs.',
                'Explore local cultural sites, historical monuments, and heritage spots.',
                'Hike nearby trails and enjoy the natural mountain scenery.',
                'Browse local markets for traditional handicrafts and regional goods.',
                'Experience authentic cuisine at the most famous local restaurants.',
                'Take a guided tour of the surrounding valleys and natural attractions.',
            ],
        ];
    }

    // -------------------------------------------------------------------------
    // Travel tips per trip type
    // -------------------------------------------------------------------------

    public function getTravelTips(string $travelType): array
    {
        return match ($travelType) {
            'family' => [
                'Book accommodations well in advance, especially during peak summer season (June–August).',
                'Carry altitude-sickness medication for children when visiting areas above 8,000 ft.',
                'Plan shorter daily drives — max 3–4 hours — for elderly family members or young children.',
                'Always carry packed snacks, bottled water, a basic first-aid kit, and warm clothing.',
            ],
            'friends' => [
                'Book a shared jeep or coaster for the group — much more economical than individual taxis.',
                'Split responsibilities: one person handles booking, one navigation, one group photography.',
                'Get a local Telenor SIM card — it has the best network coverage in the northern areas.',
                'Pool budgets for group activities like white-water rafting or jeep safaris to save costs.',
            ],
            'honeymoon' => [
                'Book a lake-view or mountain-view deluxe room well in advance for the best experience.',
                'Sunrise viewpoints are best visited at 5:30 AM — set your alarm and plan accordingly.',
                'Local handmade shawls, wooden crafts, and dried fruits make perfect romantic souvenirs.',
                'Hire a private guide for personalized sightseeing without the crowds.',
            ],
            default => [
                'Always inform someone back home of your daily itinerary and check in regularly.',
                'Join local trekking groups for added safety and great social connections.',
                'Keep emergency contact numbers for local rangers, police stations, and hospitals.',
                'Download offline maps of the northern areas before departing — signal can be weak.',
            ],
        };
    }
}