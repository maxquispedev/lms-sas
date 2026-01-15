<div class="max-w-4xl mx-auto py-8 sm:py-12 px-4 sm:px-6 lg:px-8">
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-gray-100 mb-2">
            Mi Perfil
        </h1>
        <p class="text-sm text-gray-600 dark:text-gray-400">
            Gestiona tu información personal y configuración de cuenta
        </p>
    </div>

    {{-- Flash Messages --}}
    @if(session('profile_updated'))
        <div class="mb-6 bg-green-50 dark:bg-green-900/30 border-l-4 border-green-500 dark:border-green-400 text-green-800 dark:text-green-200 px-4 py-3 rounded-r-lg shadow-sm">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-medium">{{ session('profile_updated') }}</span>
            </div>
        </div>
    @endif

    @if(session('password_updated'))
        <div class="mb-6 bg-green-50 dark:bg-green-900/30 border-l-4 border-green-500 dark:border-green-400 text-green-800 dark:text-green-200 px-4 py-3 rounded-r-lg shadow-sm">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-medium">{{ session('password_updated') }}</span>
            </div>
        </div>
    @endif

    <div class="space-y-6">
        {{-- Datos Generales --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 sm:p-8">
            <div class="mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                    Datos Generales
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Actualiza tu información personal
                </p>
            </div>

            <form wire:submit="updateProfile" class="space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Nombre
                        </label>
                        <input
                            type="text"
                            id="name"
                            wire:model="name"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:focus:border-emerald-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all duration-200 shadow-sm"
                            required
                        >
                        @error('name')
                            <p class="mt-1.5 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Apellido
                        </label>
                        <input
                            type="text"
                            id="last_name"
                            wire:model="last_name"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:focus:border-emerald-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all duration-200 shadow-sm"
                        >
                        @error('last_name')
                            <p class="mt-1.5 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Email
                    </label>
                    <input
                        type="email"
                        id="email"
                        value="{{ $email }}"
                        disabled
                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400 cursor-not-allowed"
                    >
                    <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        El email no se puede modificar.
                    </p>
                </div>

                <div class="pt-2">
                    <button
                        type="submit"
                        class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-500 dark:hover:bg-emerald-600 text-white font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md active:scale-[0.98] cursor-pointer"
                    >
                        Guardar Datos
                    </button>
                </div>
            </form>
        </div>

        {{-- Cambiar Contraseña --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 sm:p-8">
            <div class="mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                    Cambiar Contraseña
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Actualiza tu contraseña para mantener tu cuenta segura
                </p>
            </div>

            <form wire:submit="updatePassword" class="space-y-5">
                <div>
                    <label for="current_password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Contraseña Actual
                    </label>
                    <input
                        type="password"
                        id="current_password"
                        wire:model="current_password"
                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:focus:border-emerald-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all duration-200 shadow-sm"
                        required
                    >
                    @error('current_password')
                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="new_password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Nueva Contraseña
                    </label>
                    <input
                        type="password"
                        id="new_password"
                        wire:model="new_password"
                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:focus:border-emerald-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all duration-200 shadow-sm"
                        required
                    >
                    @error('new_password')
                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="new_password_confirmation" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Confirmar Nueva Contraseña
                    </label>
                    <input
                        type="password"
                        id="new_password_confirmation"
                        wire:model="new_password_confirmation"
                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:focus:border-emerald-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all duration-200 shadow-sm"
                        required
                    >
                </div>

                <div class="pt-2">
                    <button
                        type="submit"
                        class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-500 dark:hover:bg-emerald-600 text-white font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md active:scale-[0.98] cursor-pointer"
                    >
                        Cambiar Contraseña
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

