<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate(['message' => 'required|string|max:1000']);

        $apiKey = env('GROQ_API_KEY');

        // ── If no API key or key invalid, use built-in smart replies ──
        if (empty($apiKey) || !str_starts_with($apiKey, 'gsk_')) {
            return response()->json(['reply' => $this->smartReply($request->message)]);
        }

        try {
            $response = Http::timeout(20)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type'  => 'application/json',
                ])
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model'    => 'llama3-8b-8192',
                    'messages' => [
                        [
                            'role'    => 'system',
                            'content' => 'You are TripNova, a friendly AI travel assistant for Pakistan tourism. Specialize in Hunza, Skardu, Swat, Naran Kaghan, and Murree. Give concise friendly answers in max 3-4 sentences. Always include budget tips when relevant.',
                        ],
                        ['role' => 'user', 'content' => $request->message],
                    ],
                    'temperature' => 0.7,
                    'max_tokens'  => 300,
                ]);

            if ($response->successful()) {
                $reply = $response->json()['choices'][0]['message']['content'] ?? null;
                if ($reply) {
                    return response()->json(['reply' => trim($reply)]);
                }
            }

            // API failed — fall back to smart replies
            Log::warning('Groq API failed: ' . $response->status() . ' ' . $response->body());
            return response()->json(['reply' => $this->smartReply($request->message)]);

        } catch (\Throwable $e) {
            Log::error('Chatbot exception: ' . $e->getMessage());
            return response()->json(['reply' => $this->smartReply($request->message)]);
        }
    }

    // ── Built-in smart replies when GROQ is unavailable ─────────────────────
    private function smartReply(string $message): string
    {
        $msg = strtolower($message);

        // Budget questions
        if (str_contains($msg, 'budget') || str_contains($msg, 'cheap') || str_contains($msg, 'low') || str_contains($msg, 'affordable') || str_contains($msg, 'sasta')) {
            if (str_contains($msg, 'murree')) {
                return "Murree on a budget is very doable! 🏔️ Stay in Mall Road guesthouses (Rs. 2,000–4,000/night), eat at local dhabas, and avoid peak weekends. Total 2-day trip can be done in Rs. 8,000–12,000 per person including transport from Islamabad.";
            }
            if (str_contains($msg, 'hunza')) {
                return "Hunza on a budget: take a shared van from Rawalpindi (Rs. 1,500), stay in local guesthouses in Karimabad (Rs. 2,500–4,000/night). Most attractions like Baltit Fort, Attabad Lake, and viewpoints cost very little. Budget Rs. 15,000–20,000 for a 3-day trip.";
            }
            if (str_contains($msg, 'naran') || str_contains($msg, 'kaghan')) {
                return "Naran Kaghan budget tips: shared transport from Mansehra is cheapest (Rs. 500–800). Local guesthouses start at Rs. 2,000/night. Saif-ul-Malook jeep costs Rs. 4,000–6,000 shared. Plan Rs. 12,000–18,000 for a 3-day trip.";
            }
            return "For budget travel in Pakistan's north: travel in groups to split jeep costs, choose guesthouses over hotels, eat at local restaurants (Rs. 200–400/meal), and travel on weekdays. You can explore most destinations in Rs. 10,000–20,000 for 2-3 days.";
        }

        // Murree specific
        if (str_contains($msg, 'murree')) {
            return "Murree is a beautiful hill station near Islamabad at 2,300m! 🌲 Best time to visit is March–May and October–November. Top spots: Mall Road, Pindi Point, Patriata chairlift, Ayubia, and Nathiagali. From Islamabad it's only a 2-hour drive.";
        }

        // Hunza
        if (str_contains($msg, 'hunza')) {
            return "Hunza Valley is one of Pakistan's most stunning destinations! 🏔️ Must-visit: Baltit Fort, Attabad Lake (blue glacial lake), Eagle's Nest viewpoint, and Khunjerab Pass (China border). Best time: April–October. From Islamabad it's about 18 hours by road or 1 hour by PIA flight to Gilgit.";
        }

        // Skardu
        if (str_contains($msg, 'skardu')) {
            return "Skardu is the gateway to K2 and Karakoram! ⛰️ Top attractions: Shangrila Resort (Lower Kachura Lake), Deosai National Park, Cold Desert Katpana, and Shigar Fort. Best visited May–September. Fly from Islamabad (1hr) or drive via KKH (20+ hours).";
        }

        // Naran / Kaghan
        if (str_contains($msg, 'naran') || str_contains($msg, 'kaghan') || str_contains($msg, 'saif')) {
            return "Naran Kaghan is famous for Saif-ul-Malook Lake — one of Asia's most beautiful lakes! 🏔️ Also visit Babusar Top, Lalazar Plateau, and Lulusar Lake. Best time: June–September. From Islamabad it's about 6–7 hours drive via Mansehra.";
        }

        // Swat
        if (str_contains($msg, 'swat')) {
            return "Swat Valley — the Switzerland of Pakistan! 🌿 Visit Malam Jabba ski resort, Kalam Valley, Mahudand Lake, and the ancient Buddhist ruins at Butkara. Best time: April–October. Drive from Islamabad takes about 5–6 hours via Mardan.";
        }

        // Best time / season
        if (str_contains($msg, 'best time') || str_contains($msg, 'when') || str_contains($msg, 'season') || str_contains($msg, 'month')) {
            return "Best time for northern Pakistan: ☀️ Summer (May–September) is ideal for Hunza, Skardu, Naran, and Swat. Spring (March–April) is beautiful in Murree and lower valleys. Avoid July–August for road travel as monsoon causes landslides. Winter (Nov–Feb) is for snow lovers in Murree and Malam Jabba!";
        }

        // Hotel / stay
        if (str_contains($msg, 'hotel') || str_contains($msg, 'stay') || str_contains($msg, 'accommodation') || str_contains($msg, 'hostel')) {
            return "Pakistan's north has great accommodation options! 🏨 Budget: local guesthouses Rs. 2,000–4,000/night. Mid-range: Rs. 6,000–12,000/night with mountain views. Luxury: PTDC hotels and Serena resorts from Rs. 15,000+/night. Book in advance for peak season (June–August)!";
        }

        // Food
        if (str_contains($msg, 'food') || str_contains($msg, 'eat') || str_contains($msg, 'restaurant') || str_contains($msg, 'khana')) {
            return "Northern Pakistan food is amazing! 🍜 Try: Chapshuro (meat-filled bread) in Hunza, Trout fish in Naran/Swat, Sajji in Balochistan, local apricot jam everywhere in the north. Budget Rs. 300–600 per meal at local restaurants. Dhabas are cheapest and often most delicious!";
        }

        // Transport
        if (str_contains($msg, 'transport') || str_contains($msg, 'bus') || str_contains($msg, 'how to reach') || str_contains($msg, 'kaise') || str_contains($msg, 'travel')) {
            return "Getting to Pakistan's north: 🚌 Daewoo/Faisal Movers buses to Abbottabad, then local transport. Shared vans from Rawalpindi to Hunza/Gilgit cost Rs. 1,200–1,800. NATCO government buses are cheapest. Flying to Gilgit or Skardu from Islamabad saves time but costs Rs. 8,000–15,000 one way.";
        }

        // Greeting
        if (str_contains($msg, 'hello') || str_contains($msg, 'hi') || str_contains($msg, 'salam') || str_contains($msg, 'hey')) {
            return "Salam! 👋 Welcome to TripNova! I'm your Pakistan travel assistant. Ask me about destinations like Hunza, Skardu, Murree, Swat, or Naran Kaghan — or ask for budget tips, best travel times, hotels, and food recommendations!";
        }

        // Safety
        if (str_contains($msg, 'safe') || str_contains($msg, 'security') || str_contains($msg, 'danger')) {
            return "Pakistan's northern areas are generally very safe for tourists! 🛡️ Locals are extremely welcoming. Always register with local police/PTDC in remote areas, travel in groups on high-altitude passes, keep emergency number 1122 saved, and check weather before mountain passes. Enjoy your trip!";
        }

        // Default
        return "Great question about Pakistan travel! 🗺️ I can help you with destinations (Hunza, Skardu, Murree, Swat, Naran), budget tips, best travel times, hotels, food, and transport. What specific destination or information are you looking for?";
    }
}