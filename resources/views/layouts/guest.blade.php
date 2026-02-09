<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Enegence Test</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/tailwind.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <header class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    {{-- Logo / Brand --}}
                    <div class="flex items-center">
                        <a href="{{ url('/') }}" class="flex items-center gap-2">
                            <span class="text-xl font-bold text-gray-800">
                                Home
                            </span>
                        </a>
                    </div>
                    {{-- Navigation --}}
                    @if (Route::has('login'))
                        <nav class="hidden sm:flex items-center space-x-6">
                            @auth
                                <a type="button" href="{{ url('/dashboard') }}" class="btn btn-outline-light me-2">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                class="text-gray-600 hover:text-gray-900 font-medium transition">
                                    Iniciar sesión
                                </a>

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}"
                                    class="px-4 py-2 text-gray-600 hover:text-gray-900 font-medium transition">
                                        Registrarse
                                    </a>
                                @endif
                            @endauth
                        </nav>
                    @endif

                    {{-- Mobile button --}}
                    <div class="flex items-center sm:hidden">
                        <button
                            @click="open = !open"
                            class="inline-flex items-center justify-center
                                p-2 rounded-md text-gray-500
                                hover:text-gray-700 hover:bg-gray-100
                                focus:outline-none"
                        >
                            <svg class="h-6 w-6" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Mobile menu --}}
            <div
                x-data="{ open: false }"
                x-show="open"
                x-transition
                class="sm:hidden border-t border-gray-200 bg-white"
            >
                @if (Route::has('login'))
                    <nav class="px-4 py-4 space-y-2">
                    @auth
                    @else
                        <a href="{{ route('login') }}" class="block text-gray-700 hover:bg-gray-100 px-3 py-2 rounded-md">
                            Iniciar sesión
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="block text-indigo-600 hover:bg-indigo-50 px-3 py-2 rounded-md font-medium">
                                Registrarse
                            </a>
                        @endif
                    @endauth
                    </nav>
                @endif
            </div>
        </header>
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
