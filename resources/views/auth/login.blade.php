@extends('layouts.cbt', ['hideHeader' => true, 'hideFooter' => true])

@section('content')
<div class="flex-grow flex items-center justify-center p-4 relative z-10">
    <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl p-8 md:p-10 relative overflow-hidden glass-card">
        
        <!-- Decorative Shapes -->
        <div class="absolute -top-16 -right-16 w-40 h-40 bg-hikmah-neon rounded-full blur-2xl opacity-60"></div>
        <div class="absolute -bottom-16 -left-16 w-40 h-40 bg-hikmah-blue rounded-full blur-2xl opacity-40"></div>
        
        <div class="relative z-10">
            <div class="flex justify-center mb-6">
                <div class="w-20 h-20 bg-hikmah-blue rounded-2xl flex items-center justify-center text-white font-black text-3xl shadow-xl transform -rotate-3 hover:rotate-0 transition-transform">HC</div>
            </div>
            
            <h2 class="text-3xl font-extrabold text-center text-gray-800 mb-2 tracking-tight">Welcome Back!</h2>
            <p class="text-center text-gray-500 font-medium mb-8">Login to Hikmah College CBT System</p>
            
            @if (session('status'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-r-lg shadow-sm">
                    <p class="font-bold text-sm">{{ session('status') }}</p>
                    @if(session('student_id'))
                        <div class="mt-3 bg-white p-4 rounded-xl border border-green-200 shadow-sm relative overflow-hidden">
                            <!-- decoration -->
                            <div class="absolute top-0 right-0 w-16 h-16 bg-green-100 rounded-bl-full -mr-8 -mt-8"></div>
                            
                            <p class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-1 relative z-10">Student ID Anda:</p>
                            <p class="text-3xl font-black text-hikmah-blue mb-3 relative z-10">{{ session('student_id') }}</p>
                            
                            <p class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-1 relative z-10">Password:</p>
                            <div class="bg-gray-50 py-2 px-3 rounded-lg border border-gray-200 inline-block mb-2 relative z-10">
                                <p class="text-xl font-bold text-gray-800 font-mono tracking-widest">{{ session('password') }}</p>
                            </div>
                            
                            <div class="mt-2 flex items-start gap-2 text-red-500 bg-red-50 p-2 rounded-lg relative z-10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mt-0.5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                <p class="text-xs font-bold leading-tight">Simpan baik-baik informasi ini untuk login saat tryout!</p>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-lg shadow-sm">
                    <ul class="list-disc pl-5 text-sm font-bold">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Student ID atau Email Orang Tua</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 group-focus-within:text-hikmah-blue transition-colors" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" name="login" value="{{ old('login') }}" required autocomplete="username" class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-hikmah-neon/30 focus:border-hikmah-neon outline-none transition-all font-bold text-gray-800 placeholder-gray-400" placeholder="ID Student atau email orang tua">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 group-focus-within:text-hikmah-blue transition-colors" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="password" name="password" required autocomplete="current-password" class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-hikmah-neon/30 focus:border-hikmah-neon outline-none transition-all font-bold text-gray-800 placeholder-gray-400" placeholder="••••••••">
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-hikmah-neon hover:bg-hikmah-neondark text-hikmah-bluedark py-4 rounded-xl font-black text-lg tracking-wide shadow-[0_8px_20px_-6px_rgba(163,230,53,0.6)] transform hover:-translate-y-1 transition-all flex justify-center items-center gap-2">
                        <span>MASUK KE SISTEM</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </form>

            <div class="mt-8 text-center border-t border-gray-100 pt-6">
                <p class="text-sm font-medium text-gray-500">Belum memiliki akun?</p>
                <a href="{{ route('register') }}" class="inline-block mt-2 font-bold text-hikmah-blue hover:text-hikmah-bluedark transition">
                    Daftar Sekarang Disini
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
