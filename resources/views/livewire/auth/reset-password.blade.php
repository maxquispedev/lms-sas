<div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 px-4">
    <div class="w-full max-w-md bg-white dark:bg-gray-800 shadow-lg rounded-2xl p-8">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Restablecer Contraseña</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Ingresa tu nueva contraseña para completar el proceso
            </p>
        </div>

        @if(session('status'))
            <div class="mb-6 bg-primary/10 dark:bg-primary/20 border-l-4 border-primary dark:border-primary text-secondary dark:text-primary px-4 py-3 rounded-r-lg shadow-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-medium">{{ session('status') }}</span>
                </div>
            </div>
        @endif

        <form wire:submit.prevent="resetPassword" class="space-y-6">
            <div class="space-y-2">
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Correo electrónico
                </label>
                <input
                    type="email"
                    id="email"
                    wire:model.defer="email"
                    class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:focus:border-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all duration-200 shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-500"
                    placeholder="tu@correo.com"
                    required
                    readonly
                />
                @error('email')
                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Nueva Contraseña
                </label>
                <input
                    type="password"
                    id="password"
                    wire:model.defer="password"
                    class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:focus:border-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all duration-200 shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-500"
                    placeholder="Mínimo 8 caracteres"
                    required
                    autofocus
                />
                @error('password')
                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Confirmar Nueva Contraseña
                </label>
                <input
                    type="password"
                    id="password_confirmation"
                    wire:model.defer="password_confirmation"
                    class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:focus:border-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all duration-200 shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-500"
                    placeholder="Confirma tu nueva contraseña"
                    required
                />
            </div>

            <button
                type="submit"
                class="w-full inline-flex justify-center items-center px-4 py-3 bg-primary hover:bg-primary/90 text-white font-medium rounded-lg transition-colors duration-200 cursor-pointer"
            >
                Restablecer Contraseña
            </button>
        </form>

        <div class="mt-6 text-center">
            <a 
                href="{{ route('login') }}" 
                class="text-sm text-primary dark:text-primary hover:text-primary/90 dark:hover:text-primary/80 font-medium cursor-pointer"
            >
                ← Volver al Login
            </a>
        </div>
    </div>
</div>
