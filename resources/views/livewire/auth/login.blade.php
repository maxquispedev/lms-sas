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
                    class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500"
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
                    class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                    placeholder="••••••••"
                    required
                />
                @error('password')
                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <button
                type="submit"
                class="w-full inline-flex justify-center items-center px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200"
            >
                Ingresar
            </button>
        </form>

        <p class="mt-6 text-center text-xs text-gray-500 dark:text-gray-400">
            ¿No tienes cuenta? Compra un curso para obtener acceso
        </p>
    </div>
</div>

