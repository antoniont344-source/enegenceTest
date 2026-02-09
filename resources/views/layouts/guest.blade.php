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
        <header x-data="{ open: false }" class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    {{-- Logo --}}
                    @if (Route::has('login'))
                        @auth
                            <div class="flex items-center">
                                <a href="{{ url('/dashboard') }}" class="text-xl font-bold text-gray-800">
                                    Dashboard
                                </a>
                            </div>
                        @else
                            <div class="flex items-center">
                                <a href="{{ url('/') }}" class="text-xl font-bold text-gray-800">
                                    {{ config('app.name', 'Laravel') }}
                                </a>
                            </div>
                        @endauth
                    @endif
                    
                    {{-- Desktop nav --}}
                    @if (Route::has('login'))
                        <nav class="hidden sm:flex items-center space-x-6">
                            @auth
                            <span class="text-gray-600 text-sm">
                                {{ auth()->user()->name }}
                            </span>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-700 font-medium transition">
                                    Cerrar sesión
                                </button>
                            </form>
                            @else
                                <a href="{{ route('login') }}"class="text-gray-600 hover:text-gray-900 transition">
                                    Iniciar sesión
                                </a>
                                <a href="{{ route('register') }}" class="px-4 py-2 rounded-md bg-indigo-600 hover:bg-indigo-700 transition">
                                    Registrarse
                                </a>
                            @endauth
                        </nav>
                    @endif
                    
                    {{-- Mobile button --}}
                    <button @click="open = !open" type="button" class="sm:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none" aria-label="Abrir menú">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Mobile menu --}}
            <div x-show="open" x-transition @click.outside="open = false" class="sm:hidden border-t border-gray-200 bg-white">
                <nav class="px-4 py-4 space-y-2">
                    @if (Route::has('login'))
                        @auth
                            <div class="px-3 py-2 text-sm text-gray-500 text-center">
                                {{ auth()->user()->name }}
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-3 py-2 rounded-md text-red-600 hover:bg-red-50 font-medium">
                                    Cerrar sesión
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100">
                                Iniciar sesión
                            </a>
                            <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md text-indigo-600 hover:bg-indigo-50 font-medium">
                                Registrarse
                            </a>
                        @endauth
                    @endif
                </nav>
            </div>
        </header>
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
