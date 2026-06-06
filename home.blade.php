@extends('layouts.app')

@section('title', 'TripNova - Smart AI Travel Planner for Pakistan')

@section('content')
<!-- Hero Section -->
<div class="relative py-24 sm:py-32 overflow-hidden flex items-center justify-center">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        
        <!-- Welcome badge -->
        <div class="inline-flex items-center space-x-2 px-3 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/30 text-indigo-400 text-xs font-semibold mb-6 animate-pulse-slow">
            <i class="fa-solid fa-sparkles text-[10px]"></i>
            <span x-text="lang === 'en' ? 'Unleashing Custom Travel Intelligence' : 'نئی اے آئی سفری ٹیکنالوجی'">Unleashing Custom Travel Intelligence</span>
        </div>

        <!-- Main Title -->
        <h1 class="text-4xl sm:text-6xl md:text-7xl font-extrabold tracking-tight font-display text-white mb-6 leading-tight">
            <span x-text="lang === 'en' ? 'Plan Your Next Adventure' : 'اپنے اگلے یادگار سفر کا'">Plan Your Next Adventure</span> <br>
            <span class="bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-500 bg-clip-text text-transparent glow-indigo" x-text="lang === 'en' ? 'Designed by Smart AI.' : 'منصوبہ بنائیں اے آئی کے ساتھ'">Designed by Smart AI.</span>
        </h1>

        <!-- Subtitle Description -->
        <p class="max-w-2xl mx-auto text-base sm:text-xl text-gray-400 mb-10 font-medium leading-relaxed" x-text="lang === 'en' ? 'Generate day-wise itineraries, hotel suggestions, real cost breakdowns, and live weather details tailored for Hunza, Skardu, Swat, and beyond.' : 'ہنزہ، اسکردو، سوات اور مری کے لیے اپنی مرضی کے مطابق روزانہ کا شیڈول، ہوٹل کی معلومات، اور بجٹ کا تفصیلی حساب کتاب چند سیکنڈ میں حاصل کریں۔'">
            Generate day-wise itineraries, hotel suggestions, real cost breakdowns, and live weather details tailored for Hunza, Skardu, Swat, and beyond.
        </p>

        <!-- CTA Buttons -->
        <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-6">
            <a href="{{ route('planner.form') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 text-base font-bold text-white bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 hover:opacity-90 rounded-2xl transition duration-200 shadow-xl shadow-indigo-500/25 transform hover:-translate-y-0.5 space-x-2">
                <i class="fa-solid fa-wand-magic-sparkles text-sm"></i>
                <span x-text="lang === 'en' ? 'Start AI Planning' : 'منصوبہ بندی شروع کریں'">Start AI Planning</span>
            </a>
            <a href="{{ route('destinations.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 text-base font-bold text-gray-200 bg-white/5 border border-white/10 hover:bg-white/10 hover:border-white/20 rounded-2xl transition duration-200 space-x-2">
                <i class="fa-solid fa-compass text-sm"></i>
                <span x-text="lang === 'en' ? 'Explore Sights' : 'مقامات دریافت کریں'">Explore Sights</span>
            </a>
        </div>

    </div>
</div>

<!-- Visual Stats Section -->
<div class="py-12 bg-slate-950/40 border-y border-white/5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            
            <div class="p-6 glass-panel rounded-2xl space-y-2">
                <div class="text-3xl font-extrabold text-white">5,000+</div>
                <div class="text-xs text-gray-400 uppercase font-bold tracking-wider" x-text="lang === 'en' ? 'Trips Simulated' : 'منصوبہ بند اسفار'">Trips Simulated</div>
            </div>
            
            <div class="p-6 glass-panel rounded-2xl space-y-2">
                <div class="text-3xl font-extrabold text-white">100%</div>
                <div class="text-xs text-gray-400 uppercase font-bold tracking-wider" x-text="lang === 'en' ? 'Budget Optimized' : 'بجٹ کنٹرول'">Budget Optimized</div>
            </div>
            
            <div class="p-6 glass-panel rounded-2xl space-y-2">
                <div class="text-3xl font-extrabold text-white">15+</div>
                <div class="text-xs text-gray-400 uppercase font-bold tracking-wider" x-text="lang === 'en' ? 'Unique Sights' : 'مشہور مقامات'">Unique Sights</div>
            </div>
            
            <div class="p-6 glass-panel rounded-2xl space-y-2">
                <div class="text-3xl font-extrabold text-white">24/7</div>
                <div class="text-xs text-gray-400 uppercase font-bold tracking-wider" x-text="lang === 'en' ? 'Rescue Assistance' : 'ہنگامی مدد'">Rescue Assistance</div>
            </div>

        </div>
    </div>
</div>

<!-- Popular Destinations Preview Slider -->
<div class="py-24 sm:py-32">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex items-end justify-between mb-12">
            <div class="space-y-3 text-left">
                <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight font-display text-white" x-text="lang === 'en' ? 'Featured Places to Visit' : 'مشہور سیاحتی مقامات'">Featured Places to Visit</h2>
                <p class="text-gray-400 max-w-lg" x-text="lang === 'en' ? 'Browse breathtaking locations in Northern Pakistan loaded with seasonal timelines, climate info, and accommodation data.' : 'شمالی علاقہ جات کے انتہائی خوبصورت مقامات کی تفصیلات، وہاں کے موسم اور ہوٹلوں کی تفصیل حاصل کریں۔'">
                    Browse breathtaking locations in Northern Pakistan loaded with seasonal timelines, climate info, and accommodation data.
                </p>
            </div>
            <a href="{{ route('destinations.index') }}" class="hidden sm:inline-flex items-center space-x-2 text-indigo-400 hover:text-indigo-300 font-semibold text-sm transition">
                <span x-text="lang === 'en' ? 'Browse Explorer' : 'تمام مقامات دیکھیں'">Browse Explorer</span>
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <!-- Grid of Destinations -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($destinations as $destination)
            <div class="group relative rounded-2xl overflow-hidden glass-panel flex flex-col h-[400px] hover:scale-[1.02] active:scale-[0.98] transition transform duration-300">
                <!-- Cover Image -->
                <div class="absolute inset-0 bg-cover bg-center z-0 transition duration-500 group-hover:scale-105" style="background-image: url('{{ $destination->image_path }}')"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/60 to-transparent z-10"></div>
                
                <!-- Card Contents -->
                <div class="relative z-20 mt-auto p-6 space-y-3">
                    <span class="inline-block px-2.5 py-1 rounded-lg bg-indigo-500/20 border border-indigo-400/30 text-indigo-300 text-xs font-semibold uppercase tracking-wider">
                        <i class="fa-solid fa-calendar-day mr-1"></i> {{ $destination->best_season }}
                    </span>
                    <h3 class="text-2xl font-bold font-display text-white tracking-wide group-hover:text-indigo-300 transition">{{ $destination->name }}</h3>
                    <p class="text-sm text-gray-300 line-clamp-2 leading-relaxed">{{ $destination->description }}</p>
                    <div class="pt-4 border-t border-white/5 flex items-center justify-between">
                        <span class="text-xs text-gray-400">
                            Base Cost: <strong class="text-white font-bold">Rs. {{ number_format($destination->estimated_cost) }}</strong>
                        </span>
                        <a href="{{ route('destinations.show', $destination->id) }}" class="p-2 w-9 h-9 rounded-full bg-white/10 hover:bg-indigo-600 hover:text-white text-gray-200 flex items-center justify-center transition shadow">
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</div>

<!-- AI Chat & Optimization Promo -->
<div class="py-20 relative overflow-hidden bg-slate-950/40 border-t border-white/5">
    <div class="absolute inset-0 bg-gradient-to-br from-indigo-950/10 via-slate-950 to-purple-950/10 z-0"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        
        <!-- Visual Column -->
        <div class="relative flex justify-center order-2 lg:order-1">
            <div class="w-full max-w-md h-80 rounded-2xl glass-panel-glow flex flex-col justify-between p-8 border border-indigo-500/20 relative shadow-2xl overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-indigo-500/10 rounded-full blur-2xl"></div>
                <div class="flex items-center justify-between border-b border-white/5 pb-4">
                    <span class="text-indigo-400 font-bold uppercase tracking-wider text-xs flex items-center space-x-2">
                        <i class="fa-solid fa-microchip"></i>
                        <span>AI Simulation Engine</span>
                    </span>
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow shadow-emerald-500/50"></span>
                </div>
                <div class="my-6 space-y-4">
                    <div class="flex items-start space-x-3 text-xs sm:text-sm">
                        <div class="w-6 h-6 rounded-full bg-indigo-500/10 flex items-center justify-center text-indigo-400 flex-shrink-0"><i class="fa-solid fa-user"></i></div>
                        <p class="bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-gray-300">Plan a 3-day trip to Hunza under 50,000 PKR</p>
                    </div>
                    <div class="flex items-start space-x-3 text-xs sm:text-sm">
                        <div class="w-6 h-6 rounded-full bg-purple-500/10 flex items-center justify-center text-purple-400 flex-shrink-0"><i class="fa-solid fa-robot"></i></div>
                        <p class="bg-indigo-950/40 border border-indigo-500/20 rounded-xl px-4 py-2.5 text-gray-200">
                            <strong>TripNova Optimizer:</strong> Selected <strong>Hunza View Lodge</strong> (Budget). Optimized local transport & meals to guarantee total costs remain at <strong>Rs. 32,100</strong>!
                        </p>
                    </div>
                </div>
                <div class="text-right">
                    <a href="{{ route('planner.form') }}" class="text-xs text-indigo-400 hover:text-indigo-300 font-bold uppercase tracking-widest inline-flex items-center space-x-1">
                        <span>Test Optimizer</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Text Column -->
        <div class="space-y-6 text-left order-1 lg:order-2">
            <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight font-display text-white" x-text="lang === 'en' ? 'Smart Budget Optimization' : 'بجٹ کنٹرول کرنے والی جدید ٹیکنالوجی'">Smart Budget Optimization</h2>
            <p class="text-gray-300 leading-relaxed" x-text="lang === 'en' ? 'Our custom rule-based planning intelligence automatically scans your destination hotels, transportation networks, and seasonal activity logs. If your budget is low, the planner automatically shifts to cheaper stays, local public coaster transport, and warns you of costly zones.' : 'ہماری جدید اے آئی ٹیکنالوجی ہوٹلوں، ٹرانسپورٹ کے کرایوں اور تفریحی سرگرمیوں کا خودکار تجزیہ کرتی ہے۔ اگر آپ کا بجٹ کم ہے، تو یہ نظام سستے ہوٹل، مقامی سواریوں کا انتخاب کرتا ہے اور مہنگے مقامات کی نشاندہی کرتا ہے۔'">
                Our custom rule-based planning intelligence automatically scans your destination hotels, transportation networks, and seasonal activity logs. If your budget is low, the planner automatically shifts to cheaper stays, local public coaster transport, and warns you of costly zones.
            </p>
            <ul class="space-y-3 text-sm text-gray-400">
                <li class="flex items-center space-x-3"><i class="fa-solid fa-circle-check text-emerald-400"></i> <span x-text="lang === 'en' ? 'Automatically restricts luxury pricing for low budgets' : 'کم بجٹ میں شاندار سستے ہوٹلوں کا انتخاب'">Automatically restricts luxury pricing for low budgets</span></li>
                <li class="flex items-center space-x-3"><i class="fa-solid fa-circle-check text-emerald-400"></i> <span x-text="lang === 'en' ? 'Simulates realistic transport and dining ranges in PKR' : 'پاکستانی روپے میں رینٹل کار اور پبلک کوسٹر کا موازنہ'">Simulates realistic transport and dining ranges in PKR</span></li>
                <li class="flex items-center space-x-3"><i class="fa-solid fa-circle-check text-emerald-400"></i> <span x-text="lang === 'en' ? 'Warns if constraints cannot be logically met' : 'بجٹ سے تجاوز کرنے پر وارننگ الرٹ'">Warns if constraints cannot be logically met</span></li>
            </ul>
        </div>

    </div>
</div>
@endsection
