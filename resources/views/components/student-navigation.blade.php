@auth
<nav class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 shadow-lg transition-colors duration-200" x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            {{-- Logo / Nombre y Menú Principal --}}
            <div class="flex items-center gap-6">
                <a href="{{ route('student.dashboard') }}" class="flex items-center gap-2 text-xl font-semibold text-secondary dark:text-white hover:text-secondary/85 dark:hover:text-gray-200 transition-colors duration-200 cursor-pointer">
                    <img
                        src="{{ asset('img/seia-logo-new-transparent.png') }}"
                        alt="SEIA"
                        class="h-9 w-auto object-contain seia-logo-white"
                    >
                    <span class="tracking-tight">SEIA</span>
                </a>
                
                {{-- Menú de Navegación Desktop --}}
                <nav class="hidden md:flex items-center gap-4">
                    <a 
                        href="{{ route('courses.catalog') }}" 
                        class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition-colors duration-200 px-3 py-2 rounded-lg hover:bg-primary/10 dark:hover:bg-gray-800 cursor-pointer"
                    >
                        Cursos
                    </a>
                    <a 
                        href="{{ route('student.dashboard') }}" 
                        class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition-colors duration-200 px-3 py-2 rounded-lg hover:bg-primary/10 dark:hover:bg-gray-800 cursor-pointer"
                    >
                        Mis Cursos
                    </a>
                </nav>
            </div>

            {{-- Botón Menú Móvil --}}
            <div class="md:hidden flex items-center">
                <button
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-primary rounded-lg p-2 cursor-pointer"
                >
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="!mobileMenuOpen">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="mobileMenuOpen" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Usuario y Dropdown --}}
            <div class="flex items-center gap-3">
                {{-- Theme Toggle --}}
                <livewire:theme-toggle />

                <div class="relative" x-data="{ open: false }">
                    {{-- Botón del Usuario --}}
                    <button
                        @click="open = !open"
                        class="flex items-center space-x-3 text-gray-900 dark:text-white hover:text-gray-700 dark:hover:text-gray-200 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-gray-900 rounded-lg px-3 py-2 cursor-pointer"
                    >
                        {{-- Avatar --}}
                        <div class="w-10 h-10 rounded-full bg-primary dark:bg-primary flex items-center justify-center text-white font-semibold text-sm shadow-md ring-2 ring-primary/20">
                            @if(Auth::user()->avatar_url)
                                <img 
                                    src="{{ Auth::user()->avatar_url }}" 
                                    alt="{{ Auth::user()->name }}"
                                    class="w-full h-full rounded-full object-cover"
                                >
                            @else
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            @endif
                        </div>
                        <span class="text-sm font-medium hidden sm:inline">{{ Auth::user()->name }}</span>
                        <svg class="w-4 h-4 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" :class="{ 'rotate-180': open }">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    {{-- Dropdown Menu --}}
                    <div
                        x-show="open"
                        @click.away="open = false"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-xl py-1 z-50 border border-gray-200 dark:border-gray-700"
                        style="display: none;"
                    >
                        <a
                            href="{{ route('student.profile') }}"
                            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150 cursor-pointer"
                        >
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Mi Perfil
                            </div>
                        </a>

                        <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                                type="submit"
                                class="w-full text-left block px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-150 cursor-pointer"
                            >
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Cerrar Sesión
                                </div>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Menú Móvil --}}
        <div 
            x-show="mobileMenuOpen"
            @click.away="mobileMenuOpen = false"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-95"
            class="md:hidden border-t border-gray-200 dark:border-gray-700 py-4"
            style="display: none;"
        >
            <div class="flex flex-col gap-2">
                <a 
                    href="{{ route('courses.catalog') }}" 
                    class="block px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary hover:bg-primary/10 dark:hover:bg-gray-800 rounded-lg transition-colors duration-200 cursor-pointer"
                    @click="mobileMenuOpen = false"
                >
                    Cursos
                </a>
                <a 
                    href="{{ route('student.dashboard') }}" 
                    class="block px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary hover:bg-primary/10 dark:hover:bg-gray-800 rounded-lg transition-colors duration-200 cursor-pointer"
                    @click="mobileMenuOpen = false"
                >
                    Mis Cursos
                </a>
            </div>
        </div>
    </div>
</nav>
@endauth

