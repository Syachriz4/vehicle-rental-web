<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Vehicle Booking - Create Account</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "primary-fixed": "#5e5e5e",
                        "primary": "#000000",
                        "surface-tint": "#5e5e5e",
                        "on-tertiary": "#e2e2e2",
                        "secondary-fixed": "#c7c6c6",
                        "on-tertiary-container": "#ffffff",
                        "background": "#f9f9f9",
                        "on-primary-fixed-variant": "#e2e2e2",
                        "surface-container-high": "#e8e8e8",
                        "tertiary": "#3a3c3c",
                        "outline": "#777777",
                        "secondary": "#5e5e5e",
                        "on-error": "#ffffff",
                        "on-primary": "#e2e2e2",
                        "error-container": "#ffdad6",
                        "primary-fixed-dim": "#474747",
                        "surface-container": "#eeeeee",
                        "surface": "#f9f9f9",
                        "secondary-fixed-dim": "#acabab",
                        "on-secondary-fixed-variant": "#3b3b3c",
                        "primary-container": "#3b3b3b",
                        "surface-container-low": "#f3f3f3",
                        "tertiary-fixed": "#5d5f5f",
                        "on-secondary-fixed": "#1b1c1c",
                        "tertiary-container": "#737575",
                        "on-surface-variant": "#474747",
                        "outline-variant": "#c6c6c6",
                        "inverse-primary": "#c6c6c6",
                        "inverse-surface": "#2f3131",
                        "surface-variant": "#e2e2e2",
                        "surface-container-highest": "#e2e2e2",
                        "on-background": "#1a1c1c",
                        "on-surface": "#1a1c1c",
                        "surface-bright": "#f9f9f9",
                        "inverse-on-surface": "#f1f1f1",
                        "surface-dim": "#dadada",
                        "surface-container-lowest": "#ffffff",
                        "on-tertiary-fixed-variant": "#e2e2e2",
                        "on-secondary": "#ffffff",
                        "on-primary-fixed": "#ffffff",
                        "on-tertiary-fixed": "#ffffff",
                        "on-secondary-container": "#1b1c1c",
                        "on-primary-container": "#ffffff",
                        "error": "#ba1a1a",
                        "secondary-container": "#d5d4d4"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "fontFamily": {
                        "headline": ["Manrope"],
                        "body": ["Inter"],
                        "label": ["Inter"]
                    }
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 300, 'GRAD' 0, 'opsz' 24;
            font-size: 20px;
        }
        input:focus, select:focus {
            outline: none !important;
            box-shadow: none !important;
        }
    </style>
</head>
<body class="bg-surface font-body text-on-surface min-h-screen flex flex-col">

<!-- TopAppBar -->
<header class="sticky top-0 z-40 bg-surface/80 backdrop-blur-xl border-b border-surface-variant">
    <div class="flex justify-between items-center px-8 py-6 w-full max-w-screen-2xl mx-auto">
        <h1 class="text-xl font-bold tracking-widest text-primary font-headline">VEHICLE BOOKING</h1>
        <div class="flex items-center gap-4">
            <a href="{{ route('login') }}" class="text-sm font-semibold text-on-surface-variant hover:text-primary transition-colors">
                Back to Login
            </a>
        </div>
    </div>
</header>

<main class="flex-grow flex items-center justify-center py-12 px-6">
    <!-- Main Form Container -->
    <div class="w-full max-w-2xl">
        <!-- Header Section -->
        <div class="mb-12 text-center md:text-left">
            <h2 class="text-5xl font-extrabold tracking-tight text-primary mb-3 font-headline">Create Account</h2>
            <p class="text-lg text-on-surface-variant font-body">Join our fleet management system</p>
        </div>

        <!-- Registration Card -->
        <div class="bg-surface-container-lowest p-8 md:p-12 shadow-[0px_20px_50px_rgba(26,28,28,0.06)]">
            <form method="POST" action="{{ route('register') }}" class="space-y-8">
                @csrf

                <!-- Error Alert -->
                @if ($errors->any())
                    <div class="p-4 bg-red-50 border border-red-200 rounded-lg mb-6">
                        <ul class="list-none">
                            @foreach ($errors->all() as $error)
                                <li class="text-sm text-red-600">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Name Field -->
                <div class="space-y-2">
                    <label class="block text-[10px] uppercase tracking-[0.15em] font-semibold text-outline" for="name">
                        Full Name
                    </label>
                    <input 
                        class="w-full bg-[#F0F0F0] border-none py-4 px-4 text-sm font-body placeholder:text-outline-variant focus:ring-0 transition-all @error('name') border-2 border-red-500 @enderror" 
                        id="name" 
                        name="name" 
                        value="{{ old('name') }}"
                        placeholder="Enter your legal name" 
                        required 
                        type="text"/>
                    <div class="h-[1px] bg-primary transition-all duration-300"></div>
                    @error('name')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Field -->
                <div class="space-y-2">
                    <label class="block text-[10px] uppercase tracking-[0.15em] font-semibold text-outline" for="email">
                        Email Address
                    </label>
                    <input 
                        class="w-full bg-[#F0F0F0] border-none py-4 px-4 text-sm font-body placeholder:text-outline-variant focus:ring-0 transition-all @error('email') border-2 border-red-500 @enderror" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        placeholder="name@company.com" 
                        required 
                        type="email"/>
                    <div class="h-[1px] bg-primary transition-all duration-300"></div>
                    @error('email')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Department Selection -->
                <div class="space-y-2">
                    <label class="block text-[10px] uppercase tracking-[0.15em] font-semibold text-outline" for="department_id">
                        Department
                    </label>
                    <div class="relative group">
                        <select 
                            class="w-full bg-[#F0F0F0] border-none py-4 px-4 text-sm font-body appearance-none focus:ring-0 transition-all pr-10 @error('department_id') border-2 border-red-500 @enderror" 
                            id="department_id" 
                            name="department_id"
                            required>
                            <option value="">Select your department</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-outline-variant group-focus-within:text-primary transition-colors">
                            <span class="material-symbols-outlined">expand_more</span>
                        </div>
                        <div class="h-[1px] bg-primary transition-all duration-300"></div>
                    </div>
                    @error('department_id')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-[10px] uppercase tracking-[0.15em] font-semibold text-outline" for="password">
                            Password
                        </label>
                        <input 
                            class="w-full bg-[#F0F0F0] border-none py-4 px-4 text-sm font-body placeholder:text-outline-variant focus:ring-0 transition-all @error('password') border-2 border-red-500 @enderror" 
                            id="password" 
                            name="password" 
                            placeholder="••••••••" 
                            required 
                            type="password"/>
                        <div class="h-[1px] bg-primary transition-all duration-300"></div>
                        @error('password')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] uppercase tracking-[0.15em] font-semibold text-outline" for="password_confirmation">
                            Confirm Password
                        </label>
                        <input 
                            class="w-full bg-[#F0F0F0] border-none py-4 px-4 text-sm font-body placeholder:text-outline-variant focus:ring-0 transition-all" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            placeholder="••••••••" 
                            required 
                            type="password"/>
                        <div class="h-[1px] bg-primary transition-all duration-300"></div>
                    </div>
                </div>

                <!-- Terms & Conditions -->
                <div class="flex items-start gap-3 py-2">
                    <input 
                        class="mt-1 w-4 h-4 text-primary border-outline-variant focus:ring-0 rounded-sm bg-[#F0F0F0]" 
                        id="terms" 
                        name="terms"
                        required
                        type="checkbox"/>
                    <label class="text-xs text-on-surface-variant leading-relaxed" for="terms">
                        I agree to the <a href="#" class="underline text-primary cursor-pointer hover:opacity-70">Terms of Service</a> and <a href="#" class="underline text-primary cursor-pointer hover:opacity-70">Privacy Policy</a>.
                    </label>
                    @error('terms')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Primary Action -->
                <button 
                    class="w-full bg-primary text-on-primary py-5 px-6 font-headline font-bold text-sm tracking-[0.2em] uppercase hover:opacity-90 active:scale-[0.98] transition-all mt-4" 
                    type="submit">
                    Create Account
                </button>
            </form>

            <!-- Redirect Link -->
            <div class="mt-10 pt-8 border-t border-surface-variant text-center">
                <p class="text-xs font-medium text-on-surface-variant">
                    Already have an account? 
                    <a class="text-primary font-bold underline underline-offset-4 ml-1 hover:opacity-70 transition-opacity" href="{{ route('login') }}">
                        Login
                    </a>
                </p>
            </div>
        </div>

        <!-- Support Footer -->
        <div class="mt-12 text-center text-[10px] text-outline-variant font-bold uppercase tracking-widest">
            Need help? Contact support
        </div>
    </div>
</main>

<!-- Background Decorative Elements -->
<div class="fixed top-0 right-0 -z-10 w-1/3 h-full bg-surface-container-low hidden md:block"></div>
<div class="fixed bottom-0 left-0 -z-10 w-1/4 h-64 bg-surface-container opacity-50 hidden lg:block"></div>

<!-- Shared Footer -->
<footer class="w-full py-12 flex flex-col items-center gap-8 bg-[#e2e2e2] border-t border-zinc-200">
    <div class="flex flex-wrap justify-center gap-8 px-6">
        <a class="font-['Inter'] text-[10px] uppercase tracking-[0.2em] text-zinc-500 hover:text-zinc-900 transition-opacity opacity-80" href="#">Privacy</a>
        <a class="font-['Inter'] text-[10px] uppercase tracking-[0.2em] text-zinc-500 hover:text-zinc-900 transition-opacity opacity-80" href="#">Terms</a>
        <a class="font-['Inter'] text-[10px] uppercase tracking-[0.2em] text-zinc-500 hover:text-zinc-900 transition-opacity opacity-80" href="#">Support</a>
        <a class="font-['Inter'] text-[10px] uppercase tracking-[0.2em] text-zinc-500 hover:text-zinc-900 transition-opacity opacity-80" href="#">Fleet</a>
    </div>
    <div class="font-['Inter'] text-[10px] uppercase tracking-[0.2em] text-zinc-500">
        © 2026 VEHICLE BOOKING SYSTEM. ALL RIGHTS RESERVED.
    </div>
</footer>

</body>
</html>
