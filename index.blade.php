@extends('layouts.app')

@section('title', 'Explore Tourist Destinations inside Pakistan')

@section('styles')
<style>
   body{
    background-image:url('https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=1920&q=85&auto=format&fit=crop') !important;
    background-size:cover !important;
    background-position:center !important;
    background-attachment:fixed !important;
}

body::before{
    content:'';
    position:fixed;
    inset:0;
    background:rgba(255,255,255,.75);
}




    header.glass-panel { background: rgba(11,20,38,0.92) !important; border-color: rgba(255,255,255,0.07) !important; }
    header .text-slate-800 { color: #e2e8f0 !important; }
    header .text-slate-700 { color: #cbd5e1 !important; }
    footer.glass-panel { background: rgba(11,20,38,0.98) !important; border-color: rgba(255,255,255,0.05) !important; }
    footer .text-slate-800, footer .text-slate-600, footer .text-slate-500 { color: #64748b !important; }


   .page-hero{
    position:relative;
    overflow:hidden;
    background:transparent;
}




    .hero-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(to bottom, rgba(11,20,38,0.3) 0%, rgba(11,20,38,0.55) 55%, rgba(11,20,38,1) 100%);
    }
    .spotlight {
        position: absolute; width: 700px; height: 400px; border-radius: 50%;
        background: radial-gradient(ellipse, rgba(59,130,246,0.13) 0%, transparent 70%);
        top: 0; left: 50%; transform: translateX(-50%); pointer-events: none;
    }
    .orb { position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.18; pointer-events: none; }

    .dest-card {
        position: relative; border-radius: 22px; overflow: hidden; height: 460px;
        border: 1px solid rgba(255,255,255,0.08); transition: all 0.4s cubic-bezier(0.25,0.46,0.45,0.94);
        background: #111d35;
    }
    .dest-card:hover {
        border-color: rgba(59,130,246,0.5); transform: translateY(-8px);
        box-shadow: 0 32px 80px rgba(0,0,0,0.55), 0 0 50px rgba(59,130,246,0.08);
    }
    .dest-card .card-img {
        position: absolute; inset: 0;
        background-size: cover; background-position: center center;
        background-repeat: no-repeat;
        transition: transform 0.6s ease; z-index: 0;
    }
    .dest-card:hover .card-img { transform: scale(1.07); }
    





 .card-overlay{
    position:absolute;
    inset:0;
    z-index:1;
    background:linear-gradient(
        to top,
        rgba(0,0,0,0.65) 0%,
        rgba(0,0,0,0.25) 45%,
        transparent 100%
    );
}


    .dest-card:hover .card-overlay {
        background: linear-gradient(to top,
            rgba(10,16,32,1) 0%, rgba(10,16,32,0.90) 42%,
            rgba(10,16,32,0.42) 68%, rgba(10,16,32,0.12) 100%);
    }
    .card-top {
        position: absolute; top: 16px; left: 16px; right: 16px;
        z-index: 10; display: flex; justify-content: space-between; align-items: flex-start;
    }
    .card-body { position: absolute; bottom: 0; left: 0; right: 0; padding: 26px; z-index: 10; }
    .pill {
        display: inline-flex; align-items: center; gap: 5px; padding: 5px 12px;
        border-radius: 999px; font-size: 10px; font-weight: 700; letter-spacing: 0.07em; text-transform: uppercase;
    }
    .grad-text {
        background: linear-gradient(90deg, #60a5fa, #a78bfa);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    }
    .explore-btn {
        display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px;
        border-radius: 12px; font-size: 12px; font-weight: 700; color: white;
        background: linear-gradient(135deg, #3b82f6, #6366f1);
        opacity: 0; transform: translateY(6px);
        transition: opacity 0.35s ease, transform 0.35s ease;
        white-space: nowrap; box-shadow: 0 8px 24px rgba(59,130,246,0.35);
    }
    .dest-card:hover .explore-btn { opacity: 1; transform: translateY(0); }
    .card-num {
        width: 30px; height: 30px; border-radius: 50%;
        background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.12);
        display: flex; align-items: center; justify-content: center;
        font-size: 10px; font-weight: 800; color: #64748b; flex-shrink: 0;
    }
    .card-divider {
        height: 1px; margin: 14px 0;
        background: linear-gradient(to right, rgba(59,130,246,0.4), rgba(255,255,255,0.04), transparent);
    }
    .cta-section {
        background: linear-gradient(135deg, #0f1f3d 0%, #1a2f5e 50%, #0f1f3d 100%);
        border: 1px solid rgba(59,130,246,0.2); border-radius: 24px;
        position: relative; overflow: hidden;
    }

.text-white{
    color:#0f172a !important;
}

.text-slate-300,
.text-slate-400,
.text-slate-500{
    color:#475569 !important;
}

</style>
@endsection

@section('content')

@php
$imgMap = [
    'hunza'     => 'https://images.unsplash.com/photo-1627856013091-fed6e4e30025?w=800&q=80&auto=format&fit=crop',
    'skardu'    => 'https://images.unsplash.com/photo-1596701062351-df5f8adc55b9?w=800&q=80&auto=format&fit=crop',
    'murree'    => 'https://images.unsplash.com/photo-1581793745862-99fde7fa73d2?w=800&q=80&auto=format&fit=crop',
    'swat'      => 'https://images.unsplash.com/photo-1605649487212-47bdab064df7?w=800&q=80&auto=format&fit=crop',
    'naran'     => 'https://images.unsplash.com/photo-1518173946687-a4c8a383392e?w=800&q=80&auto=format&fit=crop',
    'kaghan'    => 'https://images.unsplash.com/photo-1518173946687-a4c8a383392e?w=800&q=80&auto=format&fit=crop',
    'lahore'    => 'https://images.unsplash.com/photo-1620359850125-de9b7b96bcf2?w=800&q=80&auto=format&fit=crop',
    'islamabad' => 'https://images.unsplash.com/photo-1598448834925-502a3a830f2c?w=800&q=80&auto=format&fit=crop',
    'karachi'   => 'https://images.unsplash.com/photo-1604085444693-e4d6537bd790?w=800&q=80&auto=format&fit=crop',
];
@endphp

<!-- HERO -->
<div class="page-hero py-28 sm:py-36 text-center">
    <div class="hero-mountain"></div>
    <div class="hero-overlay"></div>
    <div class="spotlight"></div>
    <div class="orb w-72 h-72 bg-blue-600" style="top:-80px;left:3%"></div>
    <div class="orb w-56 h-56 bg-indigo-700" style="top:20px;right:5%"></div>

    <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-7">
        <div class="pill bg-blue-500/12 border border-blue-500/30 text-blue-300 mx-auto w-fit">
            <i class="fa-solid fa-compass text-blue-400"></i>
            <span x-text="lang === 'en' ? 'Pakistan Tourism Explorer' : 'پاکستانی سیاحتی مقامات'">Pakistan Tourism Explorer</span>
        </div>
        <h1 class="text-5xl sm:text-7xl font-black text-white tracking-tight leading-tight">
            <span x-text="lang === 'en' ? 'Discover' : 'دریافت کریں'">Discover</span>
            <span class="block grad-text" x-text="lang === 'en' ? 'Breathtaking Valleys' : 'خوبصورت وادیاں'">Breathtaking Valleys</span>
        </h1>
        <p class="text-slate-300 max-w-xl mx-auto text-base sm:text-lg leading-relaxed"
           x-text="lang === 'en' ? 'Curated locations with live weather, pricing & verified stays — all in one place.' : 'موسم، قیمتیں اور تصدیق شدہ ہوٹل — سب ایک جگہ۔'">
            Curated locations with live weather, pricing & verified stays — all in one place.
        </p>
        <div class="flex items-center justify-center gap-3 text-sm text-slate-500 font-semibold">
            <div class="h-px w-12 bg-gradient-to-r from-transparent to-slate-700"></div>
            <span>{{ count($destinations) }} Destinations Available</span>
            <div class="h-px w-12 bg-gradient-to-l from-transparent to-slate-700"></div>
        </div>
    </div>
</div>

<!-- MAIN -->
<div style="background:#0b1426;" class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex items-center justify-between mb-10">
            <div class="flex items-center gap-4">
                <div class="h-8 w-1 rounded-full" style="background:linear-gradient(to bottom,#3b82f6,#6366f1)"></div>
                <h2 class="text-xl font-extrabold text-white" x-text="lang === 'en' ? 'All Destinations' : 'تمام مقامات'">All Destinations</h2>
            </div>
            <a href="{{ route('planner.form') }}"
               class="hidden sm:inline-flex items-center gap-2 pill bg-blue-500/10 border border-blue-500/25 text-blue-300 hover:bg-blue-500/18 transition">
                <i class="fa-solid fa-wand-magic-sparkles text-xs"></i>
                <span x-text="lang === 'en' ? 'Plan a Trip' : 'ٹرپ پلان کریں'">Plan a Trip</span>
            </a>
        </div>

        <!-- CARDS -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">
            @foreach($destinations as $i => $destination)
            @php
                $nameLower = strtolower($destination->name);
                $imgUrl = '';
                if (!empty($destination->image_path) && str_starts_with($destination->image_path, 'http')) {
                    $imgUrl = $destination->image_path;
                } else {
                    foreach ($imgMap as $key => $url) {
                        if (str_contains($nameLower, $key)) { $imgUrl = $url; break; }
                    }
                }
                if (empty($imgUrl)) {
                    $imgUrl = 'https://images.unsplash.com/photo-1527576539890-dfa815648363?w=800&q=80&auto=format&fit=crop';
                }
            @endphp

            <div class="dest-card">
                <div class="card-img" style="background-image: url('{{ $imgUrl }}')"></div>
                <div class="card-overlay"></div>

                <div class="card-top">
                    <span class="pill text-white backdrop-blur-sm"
                          style="background:rgba(37,99,235,0.80);border:1px solid rgba(96,165,250,0.35)">
                        <i class="fa-solid fa-cloud-sun text-sky-300" style="font-size:9px"></i>
                        <span x-text="lang === 'en' ? 'Weather Checked' : 'موسم درست'">Weather Checked</span>
                    </span>
                    <div class="card-num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</div>
                </div>

                <div class="card-body">
                    <span class="pill mb-3"
                          style="background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.12);color:#94a3b8">
                        <i class="fa-solid fa-calendar-day text-blue-400" style="font-size:9px"></i>
                        Best: {{ $destination->best_season }}
                    </span>

                    <h3 class="text-3xl font-black text-white tracking-tight leading-tight mb-2">
                        {{ $destination->name }}
                    </h3>
                    <p class="text-sm text-slate-400 line-clamp-2 leading-relaxed mb-3">
                        {{ $destination->description }}
                    </p>
                    <div class="card-divider"></div>
                    <div class="flex items-end justify-between gap-3">
                        <div>
                            <p class="text-[10px] text-slate-500 uppercase font-bold tracking-widest mb-1">Estimated Cost</p>
                            <p class="text-xl font-black grad-text leading-tight">
                                Rs. {{ number_format($destination->estimated_cost) }}
                                <span class="text-xs text-slate-500 font-semibold">/ Day</span>
                            </p>
                        </div>
                        <a href="{{ route('destinations.show', $destination->id) }}" class="explore-btn">
                            <span x-text="lang === 'en' ? 'Explore' : 'دیکھیں'">Explore</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if(count($destinations) === 0)
        <div class="text-center py-24 space-y-5">
            <div class="w-20 h-20 rounded-full mx-auto flex items-center justify-center text-4xl text-blue-400"
                 style="background:rgba(59,130,246,0.1);border:1px solid rgba(59,130,246,0.2)">
                <i class="fa-solid fa-map"></i>
            </div>
            <p class="text-xl font-bold text-white">No destinations added yet</p>
        </div>
        @endif

        <!-- CTA -->
        <div class="cta-section mt-20 px-8 py-14">
            <div class="orb w-56 h-56 bg-blue-600" style="top:-60px;right:-20px"></div>
            <div class="relative z-10 flex flex-col sm:flex-row items-center justify-between gap-8">
                <div class="space-y-3 text-center sm:text-left max-w-lg">
                    <p class="text-blue-400 text-xs font-bold uppercase tracking-widest">Trip Genius AI</p>
                    <h2 class="text-3xl sm:text-4xl font-black text-white leading-tight">
                        Ready to plan your
                        <span class="block grad-text">perfect Pakistan trip?</span>
                    </h2>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Our AI builds a full itinerary — hotels, daily budget, transport & activities included.
                    </p>
                </div>
                <a href="{{ route('planner.form') }}"
                   class="flex-shrink-0 inline-flex items-center gap-3 px-9 py-5 rounded-2xl font-extrabold text-sm text-white
                          transition transform hover:-translate-y-1 hover:opacity-90"
                   style="background:linear-gradient(135deg,#3b82f6,#6366f1);box-shadow:0 16px 48px rgba(59,130,246,0.35)">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                    <span x-text="lang === 'en' ? 'Start AI Planning' : 'شروع کریں'">Start AI Planning</span>
                </a>
            </div>
        </div>

    </div>
</div>

@endsection