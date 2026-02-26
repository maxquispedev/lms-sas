<div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 px-4">
    <div class="w-full max-w-md bg-white dark:bg-gray-800 shadow-lg rounded-2xl p-8">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Iniciar Sesión</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Accede a tu cuenta para continuar aprendiendo
            </p>
        </div>

        <form wire:submit.prevent="login" class="space-y-6">
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
                />
                @error('email')
                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Contraseña
                </label>
                <input
                    type="password"
                    id="password"
                    wire:model.defer="password"
                    class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:focus:border-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all duration-200 shadow-sm placeholder:text-gray-400 dark:placeholder:text-gray-500"
                    placeholder="••••••••"
                    required
                />
                @error('password')
                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end">
                <a 
                    href="{{ route('password.request') }}" 
                    class="text-sm text-primary dark:text-primary hover:text-primary/90 dark:hover:text-primary/80 font-medium cursor-pointer"
                >
                    ¿Olvidaste tu contraseña?
                </a>
            </div>

            <button
                type="submit"
                class="w-full inline-flex justify-center items-center px-4 py-3 bg-primary hover:bg-primary/90 text-white font-medium rounded-lg transition-colors duration-200 cursor-pointer"
            >
                Ingresar
            </button>
        </form>

        <p class="mt-6 text-center text-xs text-gray-500 dark:text-gray-400">
            ¿No tienes cuenta? Compra un curso para obtener acceso
        </p>
    </div>
</div>

