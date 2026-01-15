<div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 px-4">
    <div class="w-full max-w-md bg-white dark:bg-gray-800 shadow-lg rounded-2xl p-8">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Recuperar Contraseña</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña
            </p>
        </div>

        @if(session('status'))
            <div class="mb-6 bg-emerald-50 dark:bg-emerald-900/30 border-l-4 border-emerald-500 dark:border-emerald-400 text-emerald-800 dark:text-emerald-200 px-4 py-3 rounded-r-lg shadow-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-medium">{{ session('status') }}</span>
                </div>
            </div>
        @endif

        @if($emailSent)
            <div class="text-center py-8">
                <div class="mx-auto w-16 h-16 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                    Correo Enviado
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                    Hemos enviado un enlace de recuperación a <strong>{{ $email }}</strong>
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-6">
                    Revisa tu bandeja de entrada y sigue las instrucciones para restablecer tu contraseña.
                </p>
                <a 
                    href="{{ route('login') }}" 
                    class="inline-block px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition-colors duration-200 cursor-pointer"
                >
                    Volver al Login
                </a>
            </div>
        @else
            <form wire:submit.prevent="sendResetLink" class="space-y-6">
                <div class="space-y-2">
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Correo electrónico
                    </label>
                    <input
                        type="email"
                        id="email"
                        wire:model.defer="email"
                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:focus:border-emerald-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all duration-200 shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-500"
                        placeholder="tu@correo.com"
                        required
                        autofocus
                    />
                    @error('email')
                        <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="w-full inline-flex justify-center items-center px-4 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition-colors duration-200 cursor-pointer"
                >
                    Enviar Enlace de Recuperación
                </button>
            </form>

            <div class="mt-6 text-center">
                <a 
                    href="{{ route('login') }}" 
                    class="text-sm text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 font-medium cursor-pointer"
                >
                    ← Volver al Login
                </a>
            </div>
        @endif
    </div>
</div>
