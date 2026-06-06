@extends('layouts.app')

@section('title', 'Your Travel Dashboard')

@section('styles')
<style>


    /* ── Override body background for dashboard ── */
 body {
        background-image: none !important;
        background: #0a0f1e !important;
    }
    body::before { display: none !important; }

    /* ── Animated gradient hero ── */
    .hero-bg {
        background: linear-gradient(135deg, #0c1445 0%, #0e3460 30%, #0a5a7a 60%, #064e3b 100%);
        position: relative;
        overflow: hidden;
    }
    .hero-bg::before {
        content: '';
        position: absolute; inset: 0;
        background: url('https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1920&q=70&auto=format&fit=crop') center/cover no-repeat;
        opacity: 0.18;
    }
    .hero-bg::after {
        content: '';
        position: absolute; bottom: 0; left: 0; right: 0; height: 120px;
        background: linear-gradient(to bottom, transparent, #0a0f1e);
    }

    /* ── Glass card ── */
    .g-card {
        background: rgba(255,255,255,0.04);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255,255,255,0.08);
        transition: all 0.3s ease;
    }
    .g-card:hover {
        background: rgba(255,255,255,0.07);
        border-color: rgba(56,189,248,0.3);
        transform: translateY(-3px);
        box-shadow: 0 20px 60px rgba(0,0,0,0.4), 0 0 0 1px rgba(56,189,248,0.1);
    }

    /* ── Stat card ── */
    .stat-card {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.09);
        backdrop-filter: blur(16px);
        transition: all 0.3s;
    }
    .stat-card:hover {
        background: rgba(56,189,248,0.1);
        border-color: rgba(56,189,248,0.4);
        transform: translateY(-4px);
        box-shadow: 0 16px 48px rgba(56,189,248,0.15);
    }

    /* ── Trip card ── */
    .trip-card {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.07);
        backdrop-filter: blur(16px);
        transition: all 0.3s;
    }
    .trip-card:hover {
        background: rgba(255,255,255,0.08);
        border-color: rgba(56,189,248,0.35);
        transform: translateY(-2px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.35);
    }

    /* ── Glow effects ── */
    .glow-sky { box-shadow: 0 0 32px rgba(56,189,248,0.35), 0 0 64px rgba(56,189,248,0.1); }
    .glow-purple { box-shadow: 0 0 32px rgba(139,92,246,0.35); }
    .glow-emerald { box-shadow: 0 0 32px rgba(16,185,129,0.35); }

    /* ── Gradient text ── */
    .grad-text {
        background: linear-gradient(90deg, #38bdf8, #818cf8, #a78bfa);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* ── Orbs (decorative) ── */
    .orb {
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        opacity: 0.25;
        pointer-events: none;
    }

    /* Sidebar */
    .sidebar-card {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.08);
        backdrop-filter: blur(16px);
    }

    /* Badge pill */
    .pill {
        display: inline-flex; align-items: center;
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 11px; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase;
    }

    /* Scrollbar */
    ::-webkit-scrollbar { width: 4px; }
    ::-webkit-scrollbar-track { background: rgba(255,255,255,0.02); }
    ::-webkit-scrollbar-thumb { background: rgba(56,189,248,0.3); border-radius: 2px; }

    /* Override header/footer for dark page */
    header.glass-panel {
        background: rgba(10,15,30,0.85) !important;
        border-color: rgba(255,255,255,0.07) !important;
    }
    header .text-slate-800 { color: #f1f5f9 !important; }
    header .text-slate-700 { color: #cbd5e1 !important; }
    footer.glass-panel {
        background: rgba(10,15,30,0.95) !important;
        border-color: rgba(255,255,255,0.06) !important;
    }
    footer .text-slate-800, footer .text-slate-600, footer .text-slate-500 { color: #94a3b8




</style>
@endsection

@section('content')

<!-- ══════════════════════════════════════════════
     HERO SECTION
══════════════════════════════════════════════ -->
<div class="hero-bg min-h-[420px] flex items-end">
    <!-- Decorative orbs -->
    <div class="orb w-96 h-96 bg-sky-500 top-[-100px] left-[-80px]"></div>
    <div class="orb w-80 h-80 bg-violet-600 top-20 right-0"></div>
    <div class="orb w-64 h-64 bg-emerald-500 bottom-0 left-1/2"></div>

    <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24 pt-16">
        <div class="flex flex-col md:flex-row items-start md:items-end justify-between gap-10">

            <!-- Greeting -->
            <div class="space-y-5">
                <div class="pill bg-white/10 border border-white/20 text-sky-300">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-2 animate-pulse"></span>
                    Elite Explorer
                </div>
                <div>
                    <p class="text-sky-300 text-sm font-semibold mb-1 tracking-wider uppercase">Welcome back</p>
                    <h1 class="text-5xl sm:text-6xl font-black text-white leading-none tracking-tight">
                        {{ $user->name }}
                    </h1>
                </div>
                <p class="text-slate-300 text-base max-w-lg leading-relaxed">
                    Your <span class="text-sky-400 font-semibold">Trip Genius</span> dashboard — plan smarter, travel further, explore Pakistan like never before.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('planner.form') }}"
                   class="inline-flex items-center space-x-3 px-7 py-4 rounded-2xl font-bold text-sm text-white glow-sky
                          bg-gradient-to-r from-sky-500 to-indigo-600 hover:from-sky-400 hover:to-indigo-500
                          transition transform hover:-translate-y-0.5 shadow-2xl">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                    <span>Plan New Trip</span>
                </a>
                <a href="{{ route('destinations.index') }}"
                   class="inline-flex items-center space-x-3 px-7 py-4 rounded-2xl font-bold text-sm text-slate-200
                          bg-white/8 border border-white/15 hover:bg-white/12 transition">
                    <i class="fa-solid fa-compass text-sky-400"></i>
                    <span>Explore</span>
                </a>
            </div>

        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════
     STATS STRIP (floating above hero bottom)
══════════════════════════════════════════════ -->
<div class="bg-[#0a0f1e] relative z-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 -mt-14">

            <div class="stat-card rounded-2xl p-6 text-center">
                <div class="w-11 h-11 rounded-xl bg-sky-500/20 flex items-center justify-center text-sky-400 text-lg mx-auto mb-3">
                    <i class="fa-solid fa-route"></i>
                </div>
                <div class="text-4xl font-black text-white mb-1">{{ count($trips) }}</div>
                <div class="text-xs text-slate-400 uppercase font-bold tracking-widest">Trips Planned</div>
            </div>

            <div class="stat-card rounded-2xl p-6 text-center">
                <div class="w-11 h-11 rounded-xl bg-violet-500/20 flex items-center justify-center text-violet-400 text-lg mx-auto mb-3">
                    <i class="fa-solid fa-mountain"></i>
                </div>
                <div class="text-4xl font-black text-white mb-1">{{ $trips->unique('destination_id')->count() }}</div>
                <div class="text-xs text-slate-400 uppercase font-bold tracking-widest">Valleys Explored</div>
            </div>

            <div class="stat-card rounded-2xl p-6 text-center">
                <div class="w-11 h-11 rounded-xl bg-emerald-500/20 flex items-center justify-center text-emerald-400 text-lg mx-auto mb-3">
                    <i class="fa-solid fa-money-bill-wave"></i>
                </div>
                <div class="text-xl font-black text-white mb-1">Rs. {{ number_format($trips->sum('budget')) }}</div>
                <div class="text-xs text-slate-400 uppercase font-bold tracking-widest">Budget Planned</div>
            </div>

            <div class="stat-card rounded-2xl p-6 text-center">
                <div class="w-11 h-11 rounded-xl bg-amber-500/20 flex items-center justify-center text-amber-400 text-lg mx-auto mb-3">
                    <i class="fa-solid fa-trophy"></i>
                </div>
                <div class="text-2xl font-black text-white mb-1">Elite ⭐</div>
                <div class="text-xs text-slate-400 uppercase font-bold tracking-widest">Explorer Level</div>
            </div>

        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════
     MAIN GRID
══════════════════════════════════════════════ -->
<div class="bg-[#0a0f1e] min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- ── LEFT: Itineraries (col-span-2) ── -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Section Title -->
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-extrabold text-white flex items-center space-x-3">
                        <span class="w-8 h-8 rounded-lg bg-sky-500/20 flex items-center justify-center text-sky-400 text-sm">
                            <i class="fa-solid fa-suitcase-rolling"></i>
                        </span>
                        <span>Your Saved Itineraries</span>
                    </h2>
                    <span class="pill bg-white/6 border border-white/10 text-slate-400">
                        {{ count($trips) }} plan(s)
                    </span>
                </div>

                <!-- Empty State -->
                @if($trips->isEmpty())
                <div class="g-card rounded-3xl p-16 text-center space-y-6">
                    <div class="w-20 h-20 rounded-full bg-sky-500/10 border border-sky-500/20 flex items-center justify-center text-4xl mx-auto text-sky-500">
                        <i class="fa-solid fa-map"></i>
                    </div>
                    <div class="space-y-2">
                        <p class="text-xl font-bold text-white">No trips yet!</p>
                        <p class="text-sm text-slate-400 max-w-xs mx-auto">Use the AI Planner to create your first personalized Pakistan adventure in seconds.</p>
                    </div>
                    <a href="{{ route('planner.form') }}"
                       class="inline-flex items-center space-x-2 px-8 py-4 rounded-2xl font-bold text-sm text-white
                              bg-gradient-to-r from-sky-500 to-indigo-600 hover:opacity-90 transition glow-sky shadow-2xl">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                        <span>Launch AI Planner</span>
                    </a>
                </div>

                @else

                <!-- Trip Cards -->
                @foreach($trips as $trip)
                <div class="trip-card rounded-2xl p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5">

                    <!-- Left Info -->
                    <div class="flex items-center space-x-5">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-white text-2xl flex-shrink-0 shadow-xl
                                    bg-gradient-to-br from-sky-500 to-indigo-600 glow-sky">
                            <i class="fa-solid fa-map-location-dot"></i>
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center flex-wrap gap-2">
                                <h3 class="text-lg font-extrabold text-white">{{ $trip->destination->name }}</h3>
                                <span class="pill bg-sky-500/15 border border-sky-500/25 text-sky-300">{{ $trip->days }} days</span>
                                <span class="pill bg-violet-500/15 border border-violet-500/25 text-violet-300 capitalize">{{ $trip->travel_type }}</span>
                            </div>
                            <p class="text-sm text-slate-400">
                                <i class="fa-regular fa-calendar mr-1.5 text-slate-500"></i>
                                {{ $trip->created_at->format('M d, Y') }}
                            </p>
                        </div>
                    </div>

                    <!-- Budget + CTA -->
                    <div class="flex items-center gap-6 sm:border-l sm:border-white/8 sm:pl-6 w-full sm:w-auto justify-between sm:justify-end">
                        <div>
                            <p class="text-xs text-slate-500 uppercase font-bold tracking-widest mb-1">Budget</p>
                            <p class="text-2xl font-black grad-text">Rs. {{ number_format($trip->budget) }}</p>
                        </div>
                        <a href="{{ route('planner.show', $trip->id) }}"
                           class="inline-flex items-center space-x-2 px-5 py-3 rounded-xl font-bold text-sm text-white
                                  bg-white/8 border border-white/12 hover:bg-sky-600 hover:border-sky-500 transition shadow-lg">
                            <span>View</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>

                </div>
                @endforeach
                @endif

            </div>

            <!-- ── RIGHT: Sidebar ── -->
            <div class="space-y-6">

                <!-- Profile Card -->
                <div class="sidebar-card rounded-3xl p-7 space-y-5">
                    <!-- Avatar + Name -->
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-sky-400 to-indigo-600 flex items-center justify-center text-white text-2xl font-black glow-sky">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <span class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-emerald-400 border-2 border-[#0a0f1e] flex items-center justify-center">
                                <i class="fa-solid fa-check text-[8px] text-white"></i>
                            </span>
                        </div>
                        <div>
                            <h3 class="text-lg font-extrabold text-white">{{ $user->name }}</h3>
                            <p class="text-xs text-slate-400 font-medium truncate max-w-[160px]">{{ $user->email }}</p>
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="border-t border-white/6"></div>

                    <!-- Stats -->
                    <div class="space-y-1">
                        <div class="flex items-center justify-between py-2.5 text-sm">
                            <span class="text-slate-400 font-medium">Member Since</span>
                            <span class="font-bold text-white">{{ $user->created_at->format('M Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2.5 border-t border-white/5 text-sm">
                            <span class="text-slate-400 font-medium">Explorer Level</span>
                            <span class="pill bg-amber-500/15 border border-amber-500/25 text-amber-300">Elite ⭐</span>
                        </div>
                        <div class="flex items-center justify-between py-2.5 border-t border-white/5 text-sm">
                            <span class="text-slate-400 font-medium">AI Plans Created</span>
                            <span class="font-bold text-sky-400">{{ count($trips) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Achievement Card -->
                <div class="relative rounded-3xl p-7 overflow-hidden"
                     style="background: linear-gradient(135deg, #1e1060 0%, #312e81 50%, #1e3a5f 100%); border: 1px solid rgba(139,92,246,0.25);">
                    <div class="orb w-32 h-32 bg-violet-600 -top-8 -right-8"></div>
                    <div class="relative z-10 space-y-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center">
                                <i class="fa-solid fa-trophy text-amber-300 text-lg"></i>
                            </div>
                            <h3 class="font-extrabold text-white text-sm tracking-wide">Achievements</h3>
                        </div>
                        <p class="text-slate-300 text-xs leading-relaxed">
                            Certified mountain explorer! Keep planning to earn badges and unlock exclusive local rewards.
                        </p>
                        <div class="flex flex-wrap gap-2">
                            <span class="pill bg-white/10 border border-white/15 text-slate-200">🏔️ Mountain Lover</span>
                            <span class="pill bg-white/10 border border-white/15 text-slate-200">🤖 AI Pioneer</span>
                            <span class="pill bg-white/10 border border-white/15 text-slate-200">✈️ Explorer</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="sidebar-card rounded-3xl p-6 space-y-2">
                    <h3 class="text-xs font-extrabold text-slate-500 uppercase tracking-widest pb-3 border-b border-white/6 mb-2">
                        Quick Access
                    </h3>
                    <a href="{{ route('planner.form') }}"
                       class="flex items-center space-x-4 p-3 rounded-xl hover:bg-white/5 transition group">
                        <div class="w-9 h-9 rounded-xl bg-sky-500/15 flex items-center justify-center text-sky-400 group-hover:bg-sky-500/25 transition text-sm">
                            <i class="fa-solid fa-wand-magic-sparkles"></i>
                        </div>
                        <span class="font-semibold text-sm text-slate-300 group-hover:text-white transition">AI Trip Planner</span>
                        <i class="fa-solid fa-chevron-right text-xs text-slate-600 ml-auto group-hover:text-slate-400 transition"></i>
                    </a>
                    <a href="{{ route('destinations.index') }}"
                       class="flex items-center space-x-4 p-3 rounded-xl hover:bg-white/5 transition group">
                        <div class="w-9 h-9 rounded-xl bg-violet-500/15 flex items-center justify-center text-violet-400 group-hover:bg-violet-500/25 transition text-sm">
                            <i class="fa-solid fa-compass"></i>
                        </div>
                        <span class="font-semibold text-sm text-slate-300 group-hover:text-white transition">Destinations</span>
                        <i class="fa-solid fa-chevron-right text-xs text-slate-600 ml-auto group-hover:text-slate-400 transition"></i>
                    </a>
                    <a href="{{ route('companions.index') }}"
                       class="flex items-center space-x-4 p-3 rounded-xl hover:bg-white/5 transition group">
                        <div class="w-9 h-9 rounded-xl bg-emerald-500/15 flex items-center justify-center text-emerald-400 group-hover:bg-emerald-500/25 transition text-sm">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <span class="font-semibold text-sm text-slate-300 group-hover:text-white transition">Find Companions</span>
                        <i class="fa-solid fa-chevron-right text-xs text-slate-600 ml-auto group-hover:text-slate-400 transition"></i>
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection