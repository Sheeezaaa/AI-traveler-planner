@extends('layouts.app')

@section('title', $trip->destination->name . ' ' . $trip->days . '-Day Itinerary - Trip Genius')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<style>
    body { background: #1a2744 !important; }

    .d-panel {
        background: #1e3158;
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 20px;
        padding: 28px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.2);
    }
    .timeline-line {
        position: relative;
        padding-left: 26px;
        border-left: 2px solid rgba(99,130,220,0.35);
    }
    .day-dot {
        position: absolute;
        left: -31px; top: 3px;
        width: 24px; height: 24px;
        border-radius: 50%;
        background: linear-gradient(135deg,#4f8ef7,#7c6ef7);
        display: flex; align-items: center; justify-content: center;
        font-size: 9px; font-weight: 900; color: white;
        box-shadow: 0 0 0 3px #1e3158, 0 0 0 5px rgba(79,142,247,0.4);
    }
    .slot-card {
        background: #243a5e;
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 14px;
        padding: 14px 16px;
        transition: all 0.2s;
    }
    .slot-card:hover { background: #2d4a72; border-color: rgba(99,142,247,0.4); }
    .slot-label { font-size: 9px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; color: #a5b4fc; display: block; margin-bottom: 7px; }
    .slot-text { font-size: 13px; color: #c8d8f0; line-height: 1.6; font-weight: 500; }

    .expense-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 13px 16px;
        background: #243a5e;
        border: 1px solid rgba(255,255,255,0.09);
        border-radius: 14px;
        transition: all 0.2s;
    }
    .expense-row:hover { background: #2d4a72; border-color: rgba(99,142,247,0.35); }
    .expense-icon { width: 30px; height: 30px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 12px; flex-shrink: 0; }

    .hotel-row {
        background: #243a5e;
        border: 1px solid rgba(255,255,255,0.09);
        border-radius: 16px; padding: 18px 20px;
        transition: all 0.25s;
    }
    .hotel-row:hover { background: #2d4a72; border-color: rgba(99,142,247,0.4); }

    .form-input {
        width: 100%; border-radius: 12px; padding: 11px 16px;
        background: #243a5e;
        border: 1px solid rgba(255,255,255,0.15);
        color: #e8f0ff; font-size: 13px; outline: none; transition: .25s;
    }
    .form-input::placeholder { color: #6b84a8; }
    .form-input:focus { background: #2d4a72; border-color: rgba(99,142,247,0.6); }
    .form-label { display: block; font-size: 10px; font-weight: 800; color: #7a9cc8; text-transform: uppercase; letter-spacing: .08em; margin-bottom: 8px; }

    .check-item {
        background: #243a5e;
        border: 1px solid rgba(255,255,255,0.09);
        border-radius: 12px; padding: 11px 14px;
        display: flex; align-items: center; gap: 10px;
        cursor: pointer; transition: all .2s;
    }
    .check-item:hover { border-color: rgba(99,142,247,0.4); background: #2d4a72; }

    #map {
        border-radius: 14px;
        border: 1px solid rgba(255,255,255,0.15);
        height: 200px !important;
        width: 100% !important;
        z-index: 1;
    }
    .temp-ring {
        width: 72px; height: 72px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        background: rgba(79,142,247,0.15);
        border: 2px solid rgba(79,142,247,0.35);
    }
    .gtext { background: linear-gradient(90deg,#7eb8fa,#a5b4fc); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
    .pill { display:inline-flex;align-items:center;gap:5px;padding:5px 13px;border-radius:999px;font-size:10px;font-weight:700;letter-spacing:.06em;text-transform:uppercase; }
</style>
@endsection

@section('content')
<div style="background:#1a2744;min-height:100vh;" class="py-10"
     x-data="{
         currency:'PKR', rate:1, symbol:'Rs.',
         updateCurrency(c){ this.currency=c; if(c==='PKR'){this.rate=1;this.symbol='Rs.';}else if(c==='USD'){this.rate=0.0036;this.symbol='$';}else{this.rate=0.0033;this.symbol='€';} },
         formatMoney(val){ return this.symbol+' '+Math.round(val*this.rate).toLocaleString(); },
         showEmergency:false, emergencyData:null,
         async fetchEmergency(){ if(this.emergencyData){this.showEmergency=true;return;} try{ let r=await fetch('{{ route('emergency.data',$trip->destination_id) }}'); this.emergencyData=await r.json(); this.showEmergency=true; }catch(e){ this.showEmergency=true; } }
     }">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-7">

    {{-- TOP BAR --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5">
        <div class="space-y-2">
            <a href="{{ route('planner.form') }}"
               class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-widest transition"
               style="color:#7a9cc8;">
                <i class="fa-solid fa-arrow-left text-[10px]"></i> Create Another Plan
            </a>
            <h1 class="text-3xl sm:text-5xl font-black font-display text-white leading-tight">
                {{ $trip->destination->name }}
                <span class="gtext block sm:inline text-2xl sm:text-4xl">{{ $trip->days }}-Day Itinerary</span>
            </h1>
            <p class="text-sm font-semibold" style="color:#7a9cc8;">
                {{ $trip->created_at->format('M d, Y') }}
                &nbsp;·&nbsp; Mode:
                <span class="font-bold capitalize" style="color:#93c5fd;">{{ $trip->travel_type }}</span>
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            @if(!$trip->user_id)
            <form action="{{ route('planner.saveGuest',$trip->id) }}" method="POST" class="inline">
                @csrf
                <button class="pill transition" style="background:rgba(79,142,247,0.15);border:1px solid rgba(79,142,247,0.35);color:#93c5fd;">
                    <i class="fa-solid fa-cloud-arrow-up"></i> Save to Profile
                </button>
            </form>
            @endif
            <button @click="fetchEmergency()" class="pill transition"
                    style="background:rgba(239,68,68,0.15);border:1px solid rgba(239,68,68,0.35);color:#fca5a5;">
                <i class="fa-solid fa-truck-medical"></i> Emergency Help
            </button>
            <div class="flex rounded-xl overflow-hidden border text-xs font-bold" style="border-color:rgba(255,255,255,0.15);">
                @foreach(['PKR','USD','EUR'] as $c)
                <button @click="updateCurrency('{{ $c }}')"
                        :style="currency==='{{ $c }}' ? 'background:linear-gradient(135deg,#4f8ef7,#7c6ef7);color:white' : 'background:#243a5e;color:#7a9cc8'"
                        class="px-3 py-2 transition">{{ $c }}</button>
                @endforeach
            </div>
            <button onclick="window.print()" class="pill transition"
                    style="background:#243a5e;border:1px solid rgba(255,255,255,0.12);color:#c8d8f0;">
                <i class="fa-solid fa-file-pdf" style="color:#f87171;"></i> Export PDF
            </button>
        </div>
    </div>

    {{-- TWO COLUMNS --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-7">

        {{-- LEFT COLUMN --}}
        <div class="lg:col-span-2 space-y-7">

            {{-- Day Timeline --}}
            <div class="d-panel space-y-8">
                <div class="flex items-center gap-3">
                    <div class="w-1 h-7 rounded-full" style="background:linear-gradient(to bottom,#4f8ef7,#7c6ef7)"></div>
                    <h2 class="text-2xl font-bold font-display text-white">
                        <i class="fa-solid fa-route mr-2" style="color:#818cf8;"></i>Day-by-Day Journey
                    </h2>
                </div>
                <div class="timeline-line space-y-10">
                    @foreach($trip->itinerary->day_plans as $day)
                    <div class="relative">
                        <div class="day-dot">{{ $day['day'] }}</div>
                        <div class="space-y-3">
                            <div class="flex items-center flex-wrap gap-3">
                                <h3 class="text-lg font-black text-white font-display">
                                    Day {{ $day['day'] }}: {{ $day['title'] }}
                                </h3>
                                @if($day['is_optimized'] ?? false)
                                <span class="pill" style="background:rgba(16,185,129,0.15);border:1px solid rgba(16,185,129,0.35);color:#6ee7b7;">
                                    <i class="fa-solid fa-leaf" style="font-size:8px"></i> Budget Optimized
                                </span>
                                @endif
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div class="slot-card">
                                    <span class="slot-label"><i class="fa-solid fa-mug-hot mr-1"></i>Morning</span>
                                    <p class="slot-text">{{ $day['morning'] }}</p>
                                </div>
                                <div class="slot-card">
                                    <span class="slot-label"><i class="fa-solid fa-sun mr-1"></i>Afternoon</span>
                                    <p class="slot-text">{{ $day['afternoon'] }}</p>
                                </div>
                                <div class="slot-card">
                                    <span class="slot-label"><i class="fa-solid fa-cloud-moon mr-1"></i>Evening</span>
                                    <p class="slot-text">{{ $day['evening'] }}</p>
                                </div>
                                <div class="slot-card">
                                    <span class="slot-label"><i class="fa-solid fa-bed mr-1"></i>Night</span>
                                    <p class="slot-text">{{ $day['night'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Hotel Booking --}}
            <div class="d-panel space-y-6">
                <div class="flex items-center gap-3">
                    <div class="w-1 h-7 rounded-full" style="background:linear-gradient(to bottom,#7c6ef7,#a855f7)"></div>
                    <h2 class="text-2xl font-bold font-display text-white">
                        <i class="fa-solid fa-hotel mr-2" style="color:#a5b4fc;"></i>Book a Hotel
                    </h2>
                </div>
                @auth
                <div class="space-y-4">
                    @foreach($hotels as $hotel)
                    <div class="hotel-row" x-data="{ open: false }">
                        <div class="flex items-center justify-between flex-wrap gap-3">
                            <div class="flex items-center gap-4">
                                <div class="w-11 h-11 rounded-xl flex items-center justify-center"
                                     style="background:rgba(99,102,241,0.2);border:1px solid rgba(99,102,241,0.3);">
                                    <i class="fa-solid fa-hotel" style="color:#a5b4fc;"></i>
                                </div>
                                <div>
                                    <div class="flex items-center flex-wrap gap-2">
                                        <strong class="text-white font-bold">{{ $hotel->name }}</strong>
                                        <span class="pill" style="{{ $hotel->tier==='luxury'?'background:rgba(168,85,247,0.15);border:1px solid rgba(168,85,247,0.35);color:#d8b4fe':($hotel->tier==='budget'?'background:rgba(16,185,129,0.15);border:1px solid rgba(16,185,129,0.35);color:#6ee7b7':'background:rgba(79,142,247,0.15);border:1px solid rgba(79,142,247,0.35);color:#93c5fd') }}">{{ $hotel->tier }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 mt-1 text-xs" style="color:#7a9cc8;">
                                        <span><i class="fa-solid fa-star text-[9px]" style="color:#fbbf24;"></i> <strong style="color:#fcd34d;">{{ $hotel->rating }}/5</strong></span>
                                        <span class="font-black gtext">Rs. {{ number_format($hotel->price_per_night) }}/night</span>
                                    </div>
                                </div>
                            </div>
                            <button @click="open=!open" class="pill transition font-bold"
                                    :style="open?'background:rgba(239,68,68,0.15);border:1px solid rgba(239,68,68,0.35);color:#fca5a5':'background:linear-gradient(135deg,#4f8ef7,#7c6ef7);color:white;border:none;padding:9px 20px'"
                                    x-text="open?'Cancel':'Book Now'">Book Now</button>
                        </div>
                        <div x-show="open" x-transition x-cloak class="mt-5 pt-5" style="border-top:1px solid rgba(255,255,255,0.08);">
                            <form action="{{ route('hotels.book', $hotel->id) }}" method="POST" class="space-y-4">
                                @csrf
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div><label class="form-label">Full Name</label><input type="text" name="name" required value="{{ auth()->user()->name }}" class="form-input"></div>
                                    <div><label class="form-label">Email</label><input type="email" name="email" required value="{{ auth()->user()->email }}" class="form-input"></div>
                                    <div><label class="form-label">Phone</label><input type="text" name="phone" required placeholder="03XX-XXXXXXX" class="form-input"></div>
                                    <div><label class="form-label">Guests</label><input type="number" name="guests" min="1" max="10" value="2" required class="form-input"></div>
                                    <div><label class="form-label">Check-in</label><input type="date" name="check_in" required min="{{ date('Y-m-d') }}" class="form-input"></div>
                                    <div><label class="form-label">Check-out</label><input type="date" name="check_out" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" class="form-input"></div>
                                </div>
                                <button type="submit" class="w-full py-3 rounded-xl font-bold text-sm text-white transition hover:opacity-90"
                                        style="background:linear-gradient(135deg,#4f8ef7,#7c6ef7);box-shadow:0 6px 24px rgba(79,142,247,0.3);">
                                    <i class="fa-solid fa-check mr-2"></i>Confirm Booking — {{ $hotel->name }}
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-10 space-y-4 rounded-xl" style="background:#243a5e;border:1px solid rgba(255,255,255,0.1);">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center mx-auto" style="background:rgba(99,102,241,0.2);">
                        <i class="fa-solid fa-lock text-lg" style="color:#a5b4fc;"></i>
                    </div>
                    <p class="font-semibold text-sm" style="color:#c8d8f0;">Login required to book a hotel</p>
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl font-bold text-sm text-white"
                       style="background:linear-gradient(135deg,#4f8ef7,#7c6ef7);">Login to Book</a>
                </div>
                @endauth
            </div>

            {{-- Packing Checklist --}}
            <div class="d-panel space-y-5"
                 x-data="{
                     items:[
                         {n:'Warm Jacket / Windcheater',c:false},{n:'Hiking shoes / Trainers',c:false},
                         {n:'Medicines & hygiene kit',c:false},{n:'Power bank',c:false},
                         {n:'Sunscreen & sunglasses',c:false},{n:'Raincoat or umbrella',c:false},
                         {n:'Thermal socks & gloves',c:false},{n:'Water bottle (1L+)',c:false}
                     ],
                     get done(){ return this.items.filter(i=>i.c).length; }
                 }">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-1 h-7 rounded-full" style="background:linear-gradient(to bottom,#10b981,#4f8ef7)"></div>
                        <h2 class="text-xl font-bold font-display text-white">
                            <i class="fa-solid fa-clipboard-check mr-2" style="color:#34d399;"></i>Packing Checklist
                        </h2>
                    </div>
                    <span class="pill" style="background:rgba(16,185,129,0.15);border:1px solid rgba(16,185,129,0.35);color:#6ee7b7;">
                        <span x-text="done"></span>/<span x-text="items.length"></span> done
                    </span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    <template x-for="item in items" :key="item.n">
                        <div class="check-item" @click="item.c = !item.c"
                             :style="item.c ? 'border-color:rgba(16,185,129,0.4);background:rgba(16,185,129,0.1)' : ''">
                            <div class="w-5 h-5 rounded-md border-2 flex items-center justify-center flex-shrink-0 transition"
                                 :style="item.c ? 'background:linear-gradient(135deg,#10b981,#4f8ef7);border-color:transparent' : 'border-color:rgba(255,255,255,0.25)'">
                                <i class="fa-solid fa-check text-white text-[9px]" x-show="item.c"></i>
                            </div>
                            <span class="text-sm font-medium transition"
                                  :style="item.c ? 'color:#6b84a8;text-decoration:line-through' : 'color:#c8d8f0'"
                                  x-text="item.n"></span>
                        </div>
                    </template>
                </div>
            </div>

        </div>

        {{-- RIGHT SIDEBAR --}}
        <div class="space-y-6">

            {{-- Expense Breakdown --}}
            <div class="d-panel space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:rgba(79,142,247,0.2);">
                        <i class="fa-solid fa-calculator" style="color:#7eb8fa;"></i>
                    </div>
                    <h3 class="text-base font-bold text-white uppercase tracking-wide">Expense Breakdown</h3>
                </div>
                <div class="space-y-2">
                    @foreach([
                        ['icon'=>'fa-hotel',     'bg'=>'rgba(99,102,241,0.2)',  'ic'=>'color:#a5b4fc', 'label'=>'Hotels & Stay',  'val'=>'estimated_hotels'],
                        ['icon'=>'fa-car',       'bg'=>'rgba(79,142,247,0.2)',  'ic'=>'color:#93c5fd', 'label'=>'Transportation', 'val'=>'estimated_transport'],
                        ['icon'=>'fa-bowl-food', 'bg'=>'rgba(245,158,11,0.2)',  'ic'=>'color:#fcd34d', 'label'=>'Meals & Food',   'val'=>'estimated_food'],
                        ['icon'=>'fa-ticket',    'bg'=>'rgba(168,85,247,0.2)',  'ic'=>'color:#d8b4fe', 'label'=>'Sightseeing',    'val'=>'estimated_activities'],
                    ] as $exp)
                    <div class="expense-row">
                        <div class="flex items-center gap-3">
                            <div class="expense-icon" style="background:{{ $exp['bg'] }};">
                                <i class="fa-solid {{ $exp['icon'] }} text-xs" style="{{ $exp['ic'] }};"></i>
                            </div>
                            <span class="text-sm font-semibold" style="color:#c8d8f0;">{{ $exp['label'] }}</span>
                        </div>
                        <span class="text-sm font-black text-white"
                              x-text="formatMoney({{ $trip->itinerary->{$exp['val']} ?? 0 }})">
                            Rs. {{ number_format($trip->itinerary->{$exp['val']} ?? 0) }}
                        </span>
                    </div>
                    @endforeach
                    <div class="flex items-center justify-between px-4 py-4 rounded-xl mt-1"
                         style="background:linear-gradient(135deg,rgba(79,142,247,0.2),rgba(124,110,247,0.18));border:1px solid rgba(79,142,247,0.4);">
                        <span class="text-base font-extrabold text-white">Total Estimated</span>
                        <span class="text-xl font-black gtext"
                              x-text="formatMoney({{ $trip->itinerary->total_estimated ?? 0 }})">
                            Rs. {{ number_format($trip->itinerary->total_estimated ?? 0) }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between px-2 text-xs font-semibold pt-1" style="color:#6b84a8;">
                        <span>Budget: <strong style="color:#c8d8f0;">Rs. {{ number_format($trip->budget) }}</strong></span>
                        @if(($trip->itinerary->total_estimated ?? 0) <= $trip->budget)
                        <span class="flex items-center gap-1" style="color:#34d399;">
                            <i class="fa-solid fa-circle-check"></i> Within budget
                        </span>
                        @else
                        <span class="flex items-center gap-1" style="color:#fbbf24;">
                            <i class="fa-solid fa-triangle-exclamation"></i> Adjusted
                        </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Weather --}}
            <div class="d-panel space-y-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:rgba(56,189,248,0.2);">
                        <i class="fa-solid fa-cloud-sun text-sm" style="color:#38bdf8;"></i>
                    </div>
                    <span class="text-xs font-bold uppercase tracking-widest" style="color:#38bdf8;">Live Climate Report</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="space-y-1">
                        <div class="text-5xl font-black text-white">{{ $weatherData['temp'] }}<span class="text-2xl" style="color:#7a9cc8;">°C</span></div>
                        <p class="text-sm font-semibold" style="color:#c8d8f0;">
                            Condition: <span class="font-bold" style="color:#7dd3fc;">{{ $weatherData['condition'] }}</span>
                        </p>
                        <p class="text-xs" style="color:#6b84a8;">{{ $trip->destination->name }}</p>
                    </div>
                    <div class="temp-ring">
                        <i class="fa-solid {{ $weatherData['icon'] }} text-2xl" style="color:#7dd3fc;"></i>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3 pt-4" style="border-top:1px solid rgba(255,255,255,0.1);">
                    <div class="text-center p-3 rounded-xl" style="background:#243a5e;border:1px solid rgba(255,255,255,0.09);">
                        <p class="text-[10px] uppercase tracking-widest mb-1" style="color:#6b84a8;">Humidity</p>
                        <p class="text-sm font-black" style="color:#7dd3fc;">{{ $weatherData['humidity'] }}</p>
                    </div>
                    <div class="text-center p-3 rounded-xl" style="background:#243a5e;border:1px solid rgba(255,255,255,0.09);">
                        <p class="text-[10px] uppercase tracking-widest mb-1" style="color:#6b84a8;">Wind</p>
                        <p class="text-sm font-black" style="color:#7dd3fc;">{{ $weatherData['wind'] }}</p>
                    </div>
                </div>
            </div>

            {{-- MAP --}}
            <div class="d-panel space-y-3" style="padding:20px;">
                <span class="text-xs font-bold uppercase tracking-widest flex items-center gap-2" style="color:#a5b4fc;">
                    <i class="fa-solid fa-map-location"></i> Route Map
                </span>
                <div id="map"></div>
            </div>

            {{-- Travel Tips --}}
            <div class="d-panel space-y-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:rgba(245,158,11,0.2);">
                        <i class="fa-solid fa-lightbulb text-sm" style="color:#fbbf24;"></i>
                    </div>
                    <span class="text-xs font-bold uppercase tracking-widest" style="color:#fbbf24;">Traveler Tips</span>
                </div>
                <ul class="space-y-3">
                    @foreach($travelTips as $tip)
                    <li class="flex items-start gap-3 text-sm leading-relaxed" style="color:#c8d8f0;">
                        <i class="fa-solid fa-circle-check mt-0.5 flex-shrink-0 text-xs" style="color:#34d399;"></i>
                        {{ $tip }}
                    </li>
                    @endforeach
                </ul>
            </div>

        </div>
    </div>

</div>

{{-- EMERGENCY PANEL --}}
<div class="fixed inset-0 z-50 overflow-hidden" x-show="showEmergency" x-cloak x-transition>
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showEmergency=false"></div>
    <div class="absolute inset-y-0 right-0 max-w-md w-full flex flex-col overflow-y-auto shadow-2xl"
         style="background:#1e3158;border-left:1px solid rgba(239,68,68,0.3);">
        <div class="flex items-center justify-between px-6 py-5" style="border-bottom:1px solid rgba(255,255,255,0.1);">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-circle-exclamation text-xl animate-pulse" style="color:#f87171;"></i>
                <div>
                    <h3 class="text-base font-bold text-white">Emergency Assistance</h3>
                    <p class="text-[10px] uppercase font-semibold" style="color:#6b84a8;">{{ $trip->destination->name }}</p>
                </div>
            </div>
            <button @click="showEmergency=false" class="p-2 rounded-xl transition hover:bg-white/10" style="color:#7a9cc8;">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <div class="p-6 space-y-6">
            <div class="grid grid-cols-2 gap-3">
                @foreach(['Police:15','Rescue:1122','Edhi:115','PTDC:9207142'] as $e)
                @php [$lbl,$num] = explode(':',$e); @endphp
                <a href="tel:{{ $num }}" class="flex items-center gap-3 p-4 rounded-xl"
                   style="background:rgba(239,68,68,0.12);border:1px solid rgba(239,68,68,0.25);">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:rgba(239,68,68,0.2);">
                        <i class="fa-solid fa-phone text-sm" style="color:#f87171;"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest" style="color:#7a9cc8;">{{ $lbl }}</p>
                        <p class="text-base font-black" style="color:#fca5a5;">{{ $num }}</p>
                    </div>
                </a>
                @endforeach
            </div>
            <div x-show="emergencyData">
                <h4 class="text-xs font-bold uppercase tracking-widest mb-3 flex items-center gap-2" style="color:#7a9cc8;">
                    <i class="fa-solid fa-hospital-user" style="color:#f87171;"></i> Nearby Hospitals
                </h4>
                <template x-for="item in emergencyData?.hospitals ?? []">
                    <div class="flex items-center justify-between p-4 rounded-xl mb-2"
                         style="background:#243a5e;border:1px solid rgba(255,255,255,0.09);">
                        <div>
                            <strong class="text-white text-sm block" x-text="item.name"></strong>
                            <span class="text-xs" style="color:#6b84a8;" x-text="item.distance"></span>
                        </div>
                        <a :href="'tel:'+item.phone" class="pill"
                           style="background:rgba(239,68,68,0.12);border:1px solid rgba(239,68,68,0.3);color:#fca5a5;">
                            <i class="fa-solid fa-phone text-[9px]"></i>
                            <span x-text="item.phone"></span>
                        </a>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>

</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    var mapEl = document.getElementById('map');
    if (!mapEl) return;

    var coordsStr = "{{ $trip->destination->coordinates }}";
    var lat = 30.3753, lng = 69.3451;

    if (coordsStr) {
        var parts = coordsStr.split(',');
        if (parts.length === 2) {
            var p1 = parseFloat(parts[0]);
            var p2 = parseFloat(parts[1]);
            if (!isNaN(p1) && !isNaN(p2)) { lat = p1; lng = p2; }
        }
    }

    var map = L.map('map', {
        zoomControl: true,
        scrollWheelZoom: false,
        attributionControl: false
    }).setView([lat, lng], 10);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18
    }).addTo(map);

    var icon = L.divIcon({
        html: '<div style="width:16px;height:16px;border-radius:50%;background:linear-gradient(135deg,#4f8ef7,#7c6ef7);border:3px solid white;box-shadow:0 2px 10px rgba(79,142,247,0.7);"></div>',
        iconSize: [16, 16], iconAnchor: [8, 8], className: ''
    });

    L.marker([lat, lng], {icon: icon})
     .addTo(map)
     .bindPopup('<strong>{{ $trip->destination->name }}</strong>')
     .openPopup();

    setTimeout(function(){ map.invalidateSize(); }, 300);
});
</script>
@endsection