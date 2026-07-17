<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SpotAttend</title>
    <link rel="icon" href="{{ asset('logo_s.png') }}" type="image/png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 antialiased">
    <div class="min-h-screen flex">
        
        <!-- Left Side: Image / Gradient -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-indigo-600 via-blue-700 to-indigo-900 items-center justify-center relative overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
            <div class="absolute -bottom-32 -left-40 w-96 h-96 bg-white opacity-10 rounded-full blur-3xl"></div>
            <div class="absolute top-20 -right-20 w-72 h-72 bg-blue-300 opacity-20 rounded-full blur-3xl"></div>
            
            <div class="relative z-10 text-center text-white px-12">
                <img src="{{ asset('logo_s.png') }}" alt="Logo" class="w-32 h-32 mx-auto mb-8 bg-white/10 p-4 rounded-3xl backdrop-blur-md shadow-2xl border border-white/20">
                <h1 class="text-4xl font-extrabold mb-4 tracking-tight">SpotAttend</h1>
                <p class="text-lg text-blue-100 font-light max-w-md mx-auto">Manage your workforce efficiently. Log in to access your dashboard, attendance, and leave requests.</p>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12 relative">
            
            <!-- Dark Mode Toggle (Optional, if you want it) -->
            <div class="absolute top-6 right-8">
                <!-- Can be integrated later if needed -->
            </div>

            <div class="w-full max-w-md">
                
                <!-- Mobile Logo -->
                <div class="lg:hidden text-center mb-10">
                    <img src="{{ asset('logo_s.png') }}" alt="Logo" class="w-20 h-20 mx-auto mb-4 bg-indigo-50 dark:bg-gray-800 p-3 rounded-2xl shadow-sm border border-indigo-100 dark:border-gray-700">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">SpotAttend</h2>
                </div>

                <div class="text-center mb-10">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Welcome Back</h2>
                    <p class="text-gray-500 dark:text-gray-400">Please enter your details to sign in.</p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Login Input -->
                    <div>
                        <label for="login" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Email or NIK</label>
                        <input id="login" type="text" name="login" value="{{ old('login') }}" required autofocus autocomplete="username" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors placeholder-gray-400 dark:placeholder-gray-500 shadow-sm" placeholder="Enter your email or NIK">
                        <x-input-error :messages="$errors->get('login')" class="mt-2 text-red-500 text-sm" />
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex justify-between items-center mb-1.5">
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 font-medium transition-colors">Forgot password?</a>
                            @endif
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="current-password" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors placeholder-gray-400 dark:placeholder-gray-500 shadow-sm" placeholder="••••••••">
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-sm" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 transition-colors cursor-pointer">
                        <label for="remember_me" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer">Remember for 30 days</label>
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-4 rounded-xl shadow-lg shadow-indigo-600/30 hover:shadow-indigo-600/50 transition-all duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-900">
                        Sign In
                    </button>
                </form>
                
            </div>
        </div>
    </div>
</body>
</html>
