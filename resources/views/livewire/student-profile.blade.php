<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-8">
        Mi Perfil
    </h1>

    {{-- Flash Messages --}}
    @if(session('profile_updated'))
        <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('profile_updated') }}</span>
            </div>
        </div>
    @endif

    @if(session('password_updated'))
        <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('password_updated') }}</span>
            </div>
        </div>
    @endif

    <div class="space-y-6">
        {{-- Datos Generales --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-6">
                Datos Generales
            </h2>

            <form wire:submit="updateProfile" class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Nombre
                    </label>
                    <input
                        type="text"
                        id="name"
                        wire:model="name"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                        required
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Email
                    </label>
                    <input
                        type="email"
                        id="email"
                        value="{{ $email }}"
                        disabled
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-600 text-gray-500 dark:text-gray-400 cursor-not-allowed"
                    >
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        El email no se puede modificar.
                    </p>
                </div>

                <div class="pt-4">
                    <button
                        type="submit"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white font-medium rounded-lg transition-colors duration-200"
                    >
                        Guardar Datos
                    </button>
                </div>
            </form>
        </div>

        {{-- Cambiar Contraseña --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-6">
                Cambiar Contraseña
            </h2>

            <form wire:submit="updatePassword" class="space-y-4">
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Contraseña Actual
                    </label>
                    <input
                        type="password"
                        id="current_password"
                        wire:model="current_password"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                        required
                    >
                    @error('current_password')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="new_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Nueva Contraseña
                    </label>
                    <input
                        type="password"
                        id="new_password"
                        wire:model="new_password"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                        required
                    >
                    @error('new_password')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Confirmar Nueva Contraseña
                    </label>
                    <input
                        type="password"
                        id="new_password_confirmation"
                        wire:model="new_password_confirmation"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                        required
                    >
                </div>

                <div class="pt-4">
                    <button
                        type="submit"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white font-medium rounded-lg transition-colors duration-200"
                    >
                        Cambiar Contraseña
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

