@extends('layouts.app')

@section('title', 'AI Travel Planning Wizard')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-12 sm:px-6 lg:px-8">
    
    <!-- Title Section -->
    <div class="text-center mb-16 space-y-4">
        <div class="inline-flex items-center space-x-2 px-4 py-2 rounded-full bg-sky-100 border border-sky-300 text-sky-700 text-sm font-bold shadow-sm">
            <i class="fa-solid fa-wand-magic-sparkles animate-pulse"></i>
            <span x-text="lang === 'en' ? 'Step-by-Step Travel Intelligence' : 'قدم بہ قدم اے آئی سفری منصوبہ بندی'">Step-by-Step Travel Intelligence</span>
        </div>
        <h1 class="text-4xl sm:text-6xl font-extrabold font-display text-slate-800 tracking-tight" x-text="lang === 'en' ? 'AI Trip Planner Wizard' : 'اے آئی ٹرپ پلانر'">
            AI Trip Planner Wizard
        </h1>
        <p class="text-slate-600 max-w-xl mx-auto text-lg" x-text="lang === 'en' ? 'Input your parameters or speak directly to generate your personalized itinerary.' : 'اپنے سفر کے دن اور بجٹ درج کریں یا مائیک دباکر بولیں تاکہ اے آئی آپ کا شیڈول تیار کرے۔'">
            Input your parameters or speak directly to generate your personalized itinerary.
        </p>
    </div>

    <!-- Voice Control Panel -->
    <div class="glass-panel-glow rounded-3xl p-8 sm:p-10 mb-10 border border-sky-200 shadow-xl relative overflow-hidden bg-white/80" 
         x-data="{ 
            isListening: false,
            transcript: '',
            speechSupported: false,
            voiceActive: false,
            init() {
                this.speechSupported = ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window);
            },
            startSpeech() {
                if (!this.speechSupported) return;
                
                const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
                const recognition = new SpeechRecognition();
                
                recognition.lang = 'en-US';
                recognition.interimResults = false;
                recognition.maxAlternatives = 1;
                
                this.isListening = true;
                this.transcript = '';
                
                recognition.start();
                
                recognition.onresult = (event) => {
                    this.transcript = event.results[0][0].transcript;
                    this.parseVoiceCommand(this.transcript);
                };
                
                recognition.onspeechend = () => {
                    recognition.stop();
                    this.isListening = false;
                };
                
                recognition.onerror = (event) => {
                    console.error('Speech error:', event.error);
                    this.isListening = false;
                };
            },
            parseVoiceCommand(text) {
                const lower = text.toLowerCase();
                
                let destSelect = document.getElementById('destination_select');
                if (destSelect) {
                    if (lower.includes('hunza')) destSelect.value = '1';
                    else if (lower.includes('skardu')) destSelect.value = '2';
                    else if (lower.includes('murree')) destSelect.value = '3';
                    else if (lower.includes('swat')) destSelect.value = '4';
                    else if (lower.includes('naran') || lower.includes('kaghan')) destSelect.value = '5';
                }

                let daysInput = document.getElementById('days_input');
                if (daysInput) {
                    const daysMatch = lower.match(/(\d+)\s*day/);
                    if (daysMatch) {
                        daysInput.value = daysMatch[1];
                    }
                }

                let budgetInput = document.getElementById('budget_input');
                if (budgetInput) {
                    const budgetMatch = lower.replace(/,/g, '').match(/(\d+)\s*(pkr|rupee|thousand|rs|under)/);
                    const rawNumberMatch = lower.replace(/,/g, '').match(/(under|budget|of|to)\s*(\d+)/);
                    
                    if (budgetMatch) {
                        let amount = parseInt(budgetMatch[1]);
                        if (lower.includes('thousand') && amount < 1000) amount = amount * 1000;
                        budgetInput.value = amount;
                    } else if (rawNumberMatch) {
                        let amount = parseInt(rawNumberMatch[2]);
                        if (amount > 1000) budgetInput.value = amount;
                    } else {
                        const numbers = lower.match(/\b\d{4,7}\b/g);
                        if (numbers && numbers.length > 0) {
                            budgetInput.value = numbers[0];
                        }
                    }
                }

                if (lower.includes('solo')) {
                    document.getElementById('type_solo').checked = true;
                } else if (lower.includes('family')) {
                    document.getElementById('type_family').checked = true;
                } else if (lower.includes('friends') || lower.includes('friend')) {
                    document.getElementById('type_friends').checked = true;
                } else if (lower.includes('honeymoon') || lower.includes('couple')) {
                    document.getElementById('type_honeymoon').checked = true;
                }
            }
         }">
        
        <div class="flex flex-col sm:flex-row items-center justify-between gap-8 relative z-10">
            <div class="space-y-3 text-left flex-grow">
                <span class="text-sm font-bold text-sky-600 uppercase tracking-widest flex items-center space-x-2">
                    <i class="fa-solid fa-microphone"></i>
                    <span x-text="lang === 'en' ? 'Voice Control Panel' : 'آوازی منصوبہ بندی'">Voice Control Panel</span>
                </span>
                <h3 class="text-2xl font-bold text-slate-800 tracking-wide" x-text="lang === 'en' ? 'Hands-Free Trip Wizard' : 'بول کر منصوبہ بنائیں'">
                    Hands-Free Trip Wizard
                </h3>
                <p class="text-base text-slate-600 font-medium" x-text="lang === 'en' ? 'Click the mic and say: \'Plan a 3-day trip to Hunza under 50000 PKR\'' : 'مائیک پر کلک کریں اور بولیں: \'ہنزہ کا 3 دن کا سفر 50000 روپے کے اندر\'۔'">
                    Click the mic and say: "Plan a 3-day trip to Hunza under 50000 PKR"
                </p>
                <div x-show="transcript" class="p-4 bg-sky-50 border border-sky-200 rounded-xl mt-3 text-sm font-semibold text-slate-700">
                    <strong class="text-sky-600 uppercase text-xs block mb-1">We heard:</strong>
                    "<span x-text="transcript"></span>"
                </div>
            </div>
            
            <div class="flex-shrink-0">
                <button type="button" @click="startSpeech()" :disabled="!speechSupported"
                        class="w-20 h-20 rounded-full flex items-center justify-center border transition transform duration-300 shadow-xl"
                        :class="isListening 
                            ? 'bg-rose-100 border-rose-500 text-rose-500 animate-pulse scale-105 shadow-rose-500/30' 
                            : 'bg-white border-sky-300 text-sky-500 hover:scale-105 hover:bg-sky-500 hover:text-white shadow-sky-500/20'">
                    <i class="fa-solid" :class="isListening ? 'fa-microphone-lines text-3xl' : 'fa-microphone text-2xl'"></i>
                </button>
                <div x-show="!speechSupported" class="text-xs text-rose-500 font-semibold mt-3 text-center" x-text="lang === 'en' ? 'Speech not supported' : 'آواز دستیاب نہیں'">
                    Speech not supported
                </div>
            </div>
        </div>
    </div>

    <!-- Main Wizard Form -->
    <form action="{{ route('planner.plan') }}" method="POST" class="space-y-10 glass-panel rounded-3xl p-8 sm:p-12 border border-sky-100 shadow-2xl relative bg-white/90" x-data="{ step: 1 }">
        @csrf

        <!-- Progress Tracker Bar -->
        <div class="flex items-center justify-between pb-8 border-b border-sky-100 text-sm font-bold uppercase tracking-wider relative z-10">
            <button type="button" @click="step = 1" class="flex items-center space-x-3 transition" :class="step === 1 ? 'text-sky-600' : 'text-slate-400'">
                <span class="w-8 h-8 rounded-full border flex items-center justify-center text-sm" :class="step === 1 ? 'border-sky-400 bg-sky-100 text-sky-700' : 'border-slate-200 bg-slate-50'">1</span>
                <span x-text="lang === 'en' ? 'Destination' : 'منزل'">Destination</span>
            </button>
            <div class="flex-grow border-t border-sky-100 mx-6"></div>
            <button type="button" @click="step = 2" class="flex items-center space-x-3 transition" :class="step === 2 ? 'text-sky-600' : 'text-slate-400'">
                <span class="w-8 h-8 rounded-full border flex items-center justify-center text-sm" :class="step === 2 ? 'border-sky-400 bg-sky-100 text-sky-700' : 'border-slate-200 bg-slate-50'">2</span>
                <span x-text="lang === 'en' ? 'Budget & Style' : 'بجٹ اور سفری قسم'">Budget & Style</span>
            </button>
        </div>

        @if ($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-600 rounded-xl px-5 py-4 text-sm font-semibold relative z-10">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- STEP 1: Destination & Days -->
        <div x-show="step === 1" x-cloak class="space-y-8 relative z-10">
            
            <div class="space-y-4">
                <label for="destination_select" class="block text-sm font-bold text-slate-600 uppercase tracking-wider" x-text="lang === 'en' ? 'Where are you going?' : 'آپ کہاں جانا چاہتے ہیں؟'">Where are you going?</label>
                <div class="relative">
                    <select id="destination_select" name="destination_id" required class="appearance-none rounded-2xl block w-full px-5 py-5 bg-white border border-sky-200 text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 text-base shadow-sm font-medium cursor-pointer">
                        <option value="" disabled selected x-text="lang === 'en' ? 'Select a scenic valley or city' : 'سیاحتی وادی منتخب کریں'">Select a scenic valley or city</option>
                        @foreach($destinations as $dest)
                            <option value="{{ $dest->id }}" {{ old('destination_id') == $dest->id ? 'selected' : '' }}>{{ $dest->name }} (Best: {{ $dest->best_season }})</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-5 text-sky-500">
                        <i class="fa-solid fa-chevron-down text-lg"></i>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <label for="days_input" class="block text-sm font-bold text-slate-600 uppercase tracking-wider" x-text="lang === 'en' ? 'Trip Duration (Days)' : 'سفر کے دن (تعداد)'">Trip Duration (Days)</label>
                <div class="relative">
                    <input id="days_input" name="days" type="number" required min="1" max="30" value="{{ old('days', 3) }}" class="appearance-none rounded-2xl block w-full px-5 py-5 bg-white border border-sky-200 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 text-base shadow-sm font-medium" placeholder="e.g. 3">
                    <div class="absolute inset-y-0 right-0 flex items-center px-5 text-sky-500 text-sm font-bold" x-text="lang === 'en' ? 'days' : 'دن'">
                        days
                    </div>
                </div>
                <p class="text-xs text-slate-500 font-bold mt-2" x-text="lang === 'en' ? 'We recommend between 3 to 7 days for premium mountain tracks.' : 'پہاڑی علاقوں کی سیر کے لیے 3 سے 7 دن کا سفر بہترین سمجھا جاتا ہے۔'">
                    We recommend between 3 to 7 days for premium mountain tracks.
                </p>
            </div>

            <div class="pt-6 text-right">
                <button type="button" @click="step = 2" class="inline-flex items-center justify-center px-8 py-4 text-base font-bold text-white bg-sky-600 hover:bg-sky-500 rounded-xl transition duration-200 space-x-3 shadow-md">
                    <span x-text="lang === 'en' ? 'Next: Budget & Style' : 'اگلا قدم'">Next: Budget & Style</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </div>
        </div>

        <!-- STEP 2: Budget & Travel Type -->
        <div x-show="step === 2" x-cloak class="space-y-10 relative z-10">
            
            <div class="space-y-4">
                <label for="budget_input" class="block text-sm font-bold text-slate-600 uppercase tracking-wider" x-text="lang === 'en' ? 'Total Target Budget (PKR)' : 'سفر کا کل بجٹ (روپے)'">Total Target Budget (PKR)</label>
                <div class="relative">
                    <input id="budget_input" name="budget" type="number" required min="5000" step="1000" value="{{ old('budget', 50000) }}" class="appearance-none rounded-2xl block w-full px-5 py-5 bg-white border border-sky-200 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 text-base shadow-sm font-medium" placeholder="e.g. 50000">
                    <div class="absolute inset-y-0 right-0 flex items-center px-5 text-sky-500 text-sm font-bold">
                        PKR
                    </div>
                </div>
                <p class="text-xs text-slate-500 font-bold mt-2" x-text="lang === 'en' ? 'Optimizer automatically limits luxury stays if your target budget is low.' : 'اگر آپ کا بجٹ کم ہے تو اے آئی خود بخود سستے ہوٹلوں کا انتخاب کرے گا۔'">
                    Optimizer automatically limits luxury stays if your target budget is low.
                </p>
            </div>

            <div class="space-y-4">
                <label class="block text-sm font-bold text-slate-600 uppercase tracking-wider" x-text="lang === 'en' ? 'Travel Companions Type' : 'سفر کس کے ساتھ ہے؟'">Travel Companions Type</label>
                <div class="grid grid-cols-2 gap-5">
                    
                    <label class="relative flex flex-col items-center justify-center p-6 rounded-2xl bg-white border border-sky-100 cursor-pointer hover:border-sky-400 shadow-sm hover:shadow-md transition duration-200 text-center">
                        <input id="type_solo" type="radio" name="travel_type" value="solo" checked class="sr-only peer">
                        <div class="w-14 h-14 rounded-full bg-sky-50 flex items-center justify-center text-sky-500 text-2xl mb-3 peer-checked:bg-sky-500 peer-checked:text-white transition shadow-inner">
                            <i class="fa-solid fa-user-astronaut"></i>
                        </div>
                        <span class="text-base font-bold text-slate-800 block mb-1" x-text="lang === 'en' ? 'Solo' : 'اکیلا'">Solo</span>
                        <span class="text-xs text-slate-500 font-semibold" x-text="lang === 'en' ? 'Lone adventurer' : 'اکیلا مسافر'">Lone adventurer</span>
                        <div class="absolute inset-0 border-2 border-transparent peer-checked:border-sky-500 rounded-2xl pointer-events-none"></div>
                    </label>

                    <label class="relative flex flex-col items-center justify-center p-6 rounded-2xl bg-white border border-sky-100 cursor-pointer hover:border-sky-400 shadow-sm hover:shadow-md transition duration-200 text-center">
                        <input id="type_family" type="radio" name="travel_type" value="family" class="sr-only peer">
                        <div class="w-14 h-14 rounded-full bg-sky-50 flex items-center justify-center text-sky-500 text-2xl mb-3 peer-checked:bg-sky-500 peer-checked:text-white transition shadow-inner">
                            <i class="fa-solid fa-people-roof"></i>
                        </div>
                        <span class="text-base font-bold text-slate-800 block mb-1" x-text="lang === 'en' ? 'Family' : 'خاندان'">Family</span>
                        <span class="text-xs text-slate-500 font-semibold" x-text="lang === 'en' ? 'With kids or elders' : 'گھر والوں کے ساتھ'">With kids or elders</span>
                        <div class="absolute inset-0 border-2 border-transparent peer-checked:border-sky-500 rounded-2xl pointer-events-none"></div>
                    </label>

                    <label class="relative flex flex-col items-center justify-center p-6 rounded-2xl bg-white border border-sky-100 cursor-pointer hover:border-sky-400 shadow-sm hover:shadow-md transition duration-200 text-center">
                        <input id="type_friends" type="radio" name="travel_type" value="friends" class="sr-only peer">
                        <div class="w-14 h-14 rounded-full bg-sky-50 flex items-center justify-center text-sky-500 text-2xl mb-3 peer-checked:bg-sky-500 peer-checked:text-white transition shadow-inner">
                            <i class="fa-solid fa-user-group"></i>
                        </div>
                        <span class="text-base font-bold text-slate-800 block mb-1" x-text="lang === 'en' ? 'Friends' : 'دوست'">Friends</span>
                        <span class="text-xs text-slate-500 font-semibold" x-text="lang === 'en' ? 'Group scaling' : 'دوستوں کے ساتھ'">Group scaling</span>
                        <div class="absolute inset-0 border-2 border-transparent peer-checked:border-sky-500 rounded-2xl pointer-events-none"></div>
                    </label>

                    <label class="relative flex flex-col items-center justify-center p-6 rounded-2xl bg-white border border-sky-100 cursor-pointer hover:border-sky-400 shadow-sm hover:shadow-md transition duration-200 text-center">
                        <input id="type_honeymoon" type="radio" name="travel_type" value="honeymoon" class="sr-only peer">
                        <div class="w-14 h-14 rounded-full bg-sky-50 flex items-center justify-center text-sky-500 text-2xl mb-3 peer-checked:bg-sky-500 peer-checked:text-white transition shadow-inner">
                            <i class="fa-solid fa-heart-pulse"></i>
                        </div>
                        <span class="text-base font-bold text-slate-800 block mb-1" x-text="lang === 'en' ? 'Honeymoon' : 'سفرِ نکاح'">Honeymoon</span>
                        <span class="text-xs text-slate-500 font-semibold" x-text="lang === 'en' ? 'Luxury, couples choice' : 'خوبصورت رومانوی یادیں'">Luxury, couples choice</span>
                        <div class="absolute inset-0 border-2 border-transparent peer-checked:border-sky-500 rounded-2xl pointer-events-none"></div>
                    </label>

                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-between pt-6 gap-4">
                <button type="button" @click="step = 1" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-4 text-base font-bold text-slate-600 hover:text-slate-900 bg-white border border-slate-300 rounded-xl transition duration-200 space-x-2 shadow-sm">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span x-text="lang === 'en' ? 'Back' : 'واپس'">Back</span>
                </button>
                
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-10 py-4 text-base font-bold text-white bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-600 hover:to-blue-700 rounded-xl transition-all duration-200 shadow-lg shadow-sky-500/30 transform hover:-translate-y-0.5 space-x-3">
                    <i class="fa-solid fa-wand-magic-sparkles text-sm"></i>
                    <span x-text="lang === 'en' ? 'Generate AI Itinerary' : 'اے آئی شیڈول بنائیں'">Generate AI Itinerary</span>
                </button>
            </div>
        </div>

    </form>
</div>
@endsection
