<div 
    x-data="{ 
        theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light',
        init() {
            // Sync with current theme state (set by inline script)
            this.theme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
            
            // Listen for system theme changes (only if user hasn't set a preference)
            const savedTheme = localStorage.getItem('theme');
            if (!savedTheme) {
                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                    if (!localStorage.getItem('theme')) {
                        this.theme = e.matches ? 'dark' : 'light';
                        this.applyTheme();
                    }
                });
            }
        },
        toggleTheme() {
            this.theme = this.theme === 'light' ? 'dark' : 'light';
            localStorage.setItem('theme', this.theme);
            this.applyTheme();
        },
        applyTheme() {
            if (this.theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
    }"
    class="flex items-center"
>
    <button
        @click="toggleTheme"
        type="button"
        class="flex items-center justify-center w-10 h-10 rounded-lg text-white hover:text-gray-200 hover:bg-gray-800 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-900"
        aria-label="Cambiar tema"
        title="Cambiar entre tema claro y oscuro"
    >
        {{-- Sun Icon (Light Mode) --}}
        <svg 
            x-show="theme === 'light'"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 rotate-90"
            x-transition:enter-end="opacity-100 rotate-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 rotate-0"
            x-transition:leave-end="opacity-0 -rotate-90"
            class="w-5 h-5"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2"
        >
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>

        {{-- Moon Icon (Dark Mode) --}}
        <svg 
            x-show="theme === 'dark'"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -rotate-90"
            x-transition:enter-end="opacity-100 rotate-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 rotate-0"
            x-transition:leave-end="opacity-0 rotate-90"
            class="w-5 h-5"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2"
        >
            <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
        </svg>
    </button>
</div>

