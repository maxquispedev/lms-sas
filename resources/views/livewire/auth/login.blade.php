<div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 px-4">
    <div class="w-full max-w-md bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-8">
        <div class="text-center mb-8">
            @php
                /** @var \App\Support\Branding\BrandingRepository $branding */
                $branding = app(\App\Support\Branding\BrandingRepository::class);
                $brandingSettings = $branding->get();
                $brandingLogoUrl = $brandingSettings->logo_path
                    ? \Illuminate\Support\Facades\Storage::disk('public')->url($brandingSettings->logo_path)
                    : null;
            @endphp
            <img
                src="{{ $brandingLogoUrl ?? asset('img/seia-logo-new-transparent.png') }}"
                alt="{{ $brandingSettings->logo_alt }}"
                class="mx-auto h-14 sm:h-16 md:h-20 w-auto object-contain seia-logo-white"
            />
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
                    class="text-sm text-primary hover:text-primary/90 font-semibold underline underline-offset-2 cursor-pointer dark:text-white dark:hover:text-primary/90"
                >
                    ¿Olvidaste tu contraseña?
                </a>
            </div>

            <button
                type="submit"
                class="w-full inline-flex justify-center items-center px-4 py-3 bg-primary hover:bg-primary/90 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 cursor-pointer dark:bg-primary dark:hover:bg-primary/90 dark:text-white"
            >
                Ingresar
            </button>
        </form>

        <p class="mt-6 text-center text-xs text-gray-500 dark:text-gray-400">
            ¿No tienes cuenta? Compra un curso para obtener acceso
        </p>
    </div>
</div>

