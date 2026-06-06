<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Destination;
use App\Models\Hotel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed a default testing user
        User::updateOrCreate(
            ['email' => 'traveler@tripnova.com'],
            [
                'name' => 'Ali Ahmed',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );

        // 2. Seed Destinations
        $destinations = [
            [
                'name' => 'Hunza Valley',
                'description' => 'A mountainous valley in the northern part of the Gilgit-Baltistan region of Pakistan. Known for its jaw-dropping views of Rakaposhi, Baltit Fort, and the majestic turquoise Attabad Lake.',
                'best_season' => 'May to October',
                'estimated_cost' => 15000.00,
                'coordinates' => '36.3167,74.6500',
                'image_path' => 'https://images.unsplash.com/photo-1627856013091-fed6e4e30025?q=80&w=1200&auto=format&fit=crop'
            ],
            [
                'name' => 'Skardu',
                'description' => 'The gateway to some of the world\'s highest peaks, including K2. Renowned for its cold deserts, the historic Shigar Fort, Kachura Lakes (Shangrila), and breathtaking high-altitude plains of Deosai.',
                'best_season' => 'June to September',
                'estimated_cost' => 18000.00,
                'coordinates' => '35.2975,75.6333',
                'image_path' => 'https://images.unsplash.com/photo-1596701062351-df5f8adc55b9?q=80&w=1200&auto=format&fit=crop'
            ],
            [
                'name' => 'Murree & Bhurban',
                'description' => 'A popular hill station in the Galyat region, famous for its colonial-era architecture, Mall Road shopping, dense pine forests, scenic chairlifts, and refreshing cool climate year-round.',
                'best_season' => 'Year-round',
                'estimated_cost' => 8000.00,
                'coordinates' => '33.9072,73.3936',
                'image_path' => 'https://images.unsplash.com/photo-1581793745862-99fde7fa73d2?q=80&w=1200&auto=format&fit=crop'
            ],
            [
                'name' => 'Swat Valley',
                'description' => 'Often referred to as the "Switzerland of the East." Famous for its lush green meadows, clear gushing rivers, archaeological Buddhist sites, and the spectacular Kalam and Malam Jabba ski resorts.',
                'best_season' => 'April to October',
                'estimated_cost' => 10000.00,
                'coordinates' => '35.2227,72.4258',
                'image_path' => 'https://images.unsplash.com/photo-1605649487212-47bdab064df7?q=80&w=1200&auto=format&fit=crop'
            ],
            [
                'name' => 'Naran Kaghan',
                'description' => 'A mesmerizing valley located in the Mansehra District. Visited by hundreds of thousands of tourists for its alpine streams, Babusar Top vistas, Saif-ul-Malook Lake, and thrilling river rafting.',
                'best_season' => 'May to September',
                'estimated_cost' => 12000.00,
                'coordinates' => '34.9085,73.6520',
                'image_path' => 'https://images.unsplash.com/photo-1518173946687-a4c8a383392e?q=80&w=1200&auto=format&fit=crop'
            ],
            [
                'name' => 'Lahore',
                'description' => 'The cultural heart of Pakistan. Explore the historical Badshahi Mosque, the magnificent Lahore Fort, and enjoy the famous vibrant culinary scene at Food Street.',
                'best_season' => 'October to March',
                'estimated_cost' => 6000.00,
                'coordinates' => '31.5204,74.3587',
                'image_path' => 'https://images.unsplash.com/photo-1620359850125-de9b7b96bcf2?q=80&w=1200&auto=format&fit=crop'
            ],
            [
                'name' => 'Islamabad',
                'description' => 'The peaceful and lush green capital city. Renowned for the iconic Faisal Mosque, Pakistan Monument, and beautifully structured hiking trails across the Margalla Hills.',
                'best_season' => 'September to April',
                'estimated_cost' => 9000.00,
                'coordinates' => '33.6844,73.0479',
                'image_path' => 'https://images.unsplash.com/photo-1598448834925-502a3a830f2c?q=80&w=1200&auto=format&fit=crop'
            ],
            [
                'name' => 'Karachi',
                'description' => 'The City of Lights and the coastal economic hub. Enjoy the breeze at Clifton Beach, visit Mazar-e-Quaid, and experience premium luxury dining and shopping.',
                'best_season' => 'November to February',
                'estimated_cost' => 7500.00,
                'coordinates' => '24.8607,67.0011',
                'image_path' => 'https://images.unsplash.com/photo-1604085444693-e4d6537bd790?q=80&w=1200&auto=format&fit=crop'
            ]
        ];

        foreach ($destinations as $destData) {
            $destination = Destination::updateOrCreate(
                ['name' => $destData['name']],
                $destData
            );

            // 3. Seed Hotels for each destination
            $hotels = [];
            
            if ($destination->name === 'Lahore') {
                $hotels = [
                    ['name' => 'Lahore Backpackers', 'price_per_night' => 2000.00, 'rating' => 4.0, 'tier' => 'budget', 'contact_info' => '+92-300-6667771', 'image_path' => 'https://images.unsplash.com/photo-1555854877-bab0e564b8d5?q=80&w=600&auto=format&fit=crop'],
                    ['name' => 'Nishat Hotel Johar Town', 'price_per_night' => 12000.00, 'rating' => 4.5, 'tier' => 'standard', 'contact_info' => '+92-300-6667772', 'image_path' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=600&auto=format&fit=crop'],
                    ['name' => 'Pearl Continental Lahore', 'price_per_night' => 25000.00, 'rating' => 4.8, 'tier' => 'luxury', 'contact_info' => '+92-300-6667773', 'image_path' => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?q=80&w=600&auto=format&fit=crop']
                ];
            } elseif ($destination->name === 'Islamabad') {
                $hotels = [
                    ['name' => 'Envoy Continental', 'price_per_night' => 4000.00, 'rating' => 4.1, 'tier' => 'budget', 'contact_info' => '+92-300-7778881', 'image_path' => 'https://images.unsplash.com/photo-1555854877-bab0e564b8d5?q=80&w=600&auto=format&fit=crop'],
                    ['name' => 'Ramada by Wyndham', 'price_per_night' => 14000.00, 'rating' => 4.4, 'tier' => 'standard', 'contact_info' => '+92-300-7778882', 'image_path' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=600&auto=format&fit=crop'],
                    ['name' => 'Islamabad Serena Hotel', 'price_per_night' => 35000.00, 'rating' => 4.9, 'tier' => 'luxury', 'contact_info' => '+92-300-7778883', 'image_path' => 'https://images.unsplash.com/photo-1582719508461-905c673771fd?q=80&w=600&auto=format&fit=crop']
                ];
            } elseif ($destination->name === 'Karachi') {
                $hotels = [
                    ['name' => 'Karachi Inn', 'price_per_night' => 3000.00, 'rating' => 3.8, 'tier' => 'budget', 'contact_info' => '+92-300-8889991', 'image_path' => 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?q=80&w=600&auto=format&fit=crop'],
                    ['name' => 'Avari Towers Karachi', 'price_per_night' => 16000.00, 'rating' => 4.5, 'tier' => 'standard', 'contact_info' => '+92-300-8889992', 'image_path' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=600&auto=format&fit=crop'],
                    ['name' => 'Mövenpick Hotel Karachi', 'price_per_night' => 28000.00, 'rating' => 4.7, 'tier' => 'luxury', 'contact_info' => '+92-300-8889993', 'image_path' => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?q=80&w=600&auto=format&fit=crop']
                ];
            } elseif ($destination->name === 'Hunza Valley') {
                $hotels = [
                    ['name' => 'Hunza View Lodge & Hostel', 'price_per_night' => 3500.00, 'rating' => 4.1, 'tier' => 'budget', 'contact_info' => '+92-300-1112221', 'image_path' => 'https://images.unsplash.com/photo-1555854877-bab0e564b8d5?q=80&w=600&auto=format&fit=crop'],
                    ['name' => 'Eagles Nest Hotel Duiker', 'price_per_night' => 9500.00, 'rating' => 4.5, 'tier' => 'standard', 'contact_info' => '+92-300-1112222', 'image_path' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=600&auto=format&fit=crop'],
                    ['name' => 'Luxus Hunza Resort', 'price_per_night' => 24000.00, 'rating' => 4.9, 'tier' => 'luxury', 'contact_info' => '+92-300-1112223', 'image_path' => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?q=80&w=600&auto=format&fit=crop']
                ];
            } elseif ($destination->name === 'Skardu') {
                $hotels = [
                    ['name' => 'Karakoram Inn & Guest House', 'price_per_night' => 3000.00, 'rating' => 4.0, 'tier' => 'budget', 'contact_info' => '+92-300-2223331', 'image_path' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=600&auto=format&fit=crop'],
                    ['name' => 'Serena Shigar Fort heritage', 'price_per_night' => 15000.00, 'rating' => 4.7, 'tier' => 'standard', 'contact_info' => '+92-300-2223332', 'image_path' => 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?q=80&w=600&auto=format&fit=crop'],
                    ['name' => 'Shangrila Resort Skardu', 'price_per_night' => 28000.00, 'rating' => 4.9, 'tier' => 'luxury', 'contact_info' => '+92-300-2223333', 'image_path' => 'https://images.unsplash.com/photo-1582719508461-905c673771fd?q=80&w=600&auto=format&fit=crop']
                ];
            } elseif ($destination->name === 'Murree & Bhurban') {
                $hotels = [
                    ['name' => 'Pine Wood Guest House', 'price_per_night' => 2500.00, 'rating' => 3.9, 'tier' => 'budget', 'contact_info' => '+92-300-3334441', 'image_path' => 'https://images.unsplash.com/photo-1555854877-bab0e564b8d5?q=80&w=600&auto=format&fit=crop'],
                    ['name' => 'Lockwood Hotel Murree', 'price_per_night' => 7500.00, 'rating' => 4.2, 'tier' => 'standard', 'contact_info' => '+92-300-3334442', 'image_path' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=600&auto=format&fit=crop'],
                    ['name' => 'Pearl Continental Bhurban', 'price_per_night' => 22000.00, 'rating' => 4.8, 'tier' => 'luxury', 'contact_info' => '+92-300-3334443', 'image_path' => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?q=80&w=600&auto=format&fit=crop']
                ];
            } elseif ($destination->name === 'Swat Valley') {
                $hotels = [
                    ['name' => 'Kalam River Lodge', 'price_per_night' => 3000.00, 'rating' => 4.0, 'tier' => 'budget', 'contact_info' => '+92-300-4445551', 'image_path' => 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?q=80&w=600&auto=format&fit=crop'],
                    ['name' => 'White Palace Marghazar Hotel', 'price_per_night' => 8500.00, 'rating' => 4.4, 'tier' => 'standard', 'contact_info' => '+92-300-4445552', 'image_path' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=600&auto=format&fit=crop'],
                    ['name' => 'Serena Hotel Swat', 'price_per_night' => 19000.00, 'rating' => 4.8, 'tier' => 'luxury', 'contact_info' => '+92-300-4445553', 'image_path' => 'https://images.unsplash.com/photo-1582719508461-905c673771fd?q=80&w=600&auto=format&fit=crop']
                ];
            } else { // Naran Kaghan
                $hotels = [
                    ['name' => 'Naran Riverside Motel', 'price_per_night' => 3200.00, 'rating' => 3.9, 'tier' => 'budget', 'contact_info' => '+92-300-5556661', 'image_path' => 'https://images.unsplash.com/photo-1555854877-bab0e564b8d5?q=80&w=600&auto=format&fit=crop'],
                    ['name' => 'Pine Park Hotel Shogran', 'price_per_night' => 8000.00, 'rating' => 4.3, 'tier' => 'standard', 'contact_info' => '+92-300-5556662', 'image_path' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=600&auto=format&fit=crop'],
                    ['name' => 'Maisonette Hotel & Resort Naran', 'price_per_night' => 20000.00, 'rating' => 4.6, 'tier' => 'luxury', 'contact_info' => '+92-300-5556663', 'image_path' => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?q=80&w=600&auto=format&fit=crop']
                ];
            }

            foreach ($hotels as $hotelData) {
                Hotel::updateOrCreate(
                    [
                        'destination_id' => $destination->id,
                        'name' => $hotelData['name']
                    ],
                    $hotelData
                );
            }
        }
    }
}
