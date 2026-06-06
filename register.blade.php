@extends('layouts.app')

@section('title', 'Sign Up on TripNova')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 glass-panel rounded-3xl p-8 sm:p-10 border border-white/10 shadow-2xl relative">
        
        <!-- Glowing Ambient Circle -->
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-500/10 rounded-full blur-2xl"></div>
        <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-purple-500/10 rounded-full blur-2xl"></div>

        <div class="text-center relative z-10">
            <h2 class="text-3xl font-extrabold font-display tracking-tight text-white" x-text="lang === 'en' ? 'Create Account' : 'نیا اکاؤنٹ بنائیں'">
                Create Account
            </h2>
            <p class="mt-2 text-sm text-gray-400 font-medium">
                <span x-text="lang === 'en' ? 'Already registered?' : 'پہلے سے رجسٹرڈ ہیں؟'">Already registered?</span>
                <a href="{{ route('login') }}" class="font-bold text-indigo-400 hover:text-indigo-300 transition duration-150 ml-1" x-text="lang === 'en' ? 'Sign in here' : 'لاگ ان کریں'">
                    Sign in here
                </a>
            </p>
        </div>

        @if ($errors->any())
        <div class="bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded-xl px-4 py-3 text-xs font-semibold relative z-10">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form class="mt-8 space-y-6 relative z-10" action="{{ route('register') }}" method="POST">
            @csrf
            
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2" x-text="lang === 'en' ? 'Full Name' : 'پورا نام'">Full Name</label>
                    <input id="name" name="name" type="text" autocomplete="name" required value="{{ old('name') }}" class="appearance-none rounded-xl relative block w-full px-4 py-3 bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white/10 transition duration-200 text-sm" placeholder="Ali Ahmed">
                </div>
                <div>
                    <label for="email-address" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2" x-text="lang === 'en' ? 'Email Address' : 'ای میل ایڈریس'">Email Address</label>
                    <input id="email-address" name="email" type="email" autocomplete="email" required value="{{ old('email') }}" class="appearance-none rounded-xl relative block w-full px-4 py-3 bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white/10 transition duration-200 text-sm" placeholder="traveler@tripnova.com">
                </div>
                <div>
                    <label for="password" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2" x-text="lang === 'en' ? 'Password' : 'پاس ورڈ'">Password</label>
                    <input id="password" name="password" type="password" autocomplete="new-password" required class="appearance-none rounded-xl relative block w-full px-4 py-3 bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white/10 transition duration-200 text-sm" placeholder="••••••••">
                </div>
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2" x-text="lang === 'en' ? 'Confirm Password' : 'پاس ورڈ کی تصدیق'">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required class="appearance-none rounded-xl relative block w-full px-4 py-3 bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white/10 transition duration-200 text-sm" placeholder="••••••••">
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-3.5 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-md shadow-indigo-500/20 transform hover:-translate-y-0.5 transition duration-200">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fa-solid fa-user-plus text-indigo-200 group-hover:text-white transition"></i>
                    </span>
                    <span x-text="lang === 'en' ? 'Create Account' : 'اکاؤنٹ بنائیں'">Create Account</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
