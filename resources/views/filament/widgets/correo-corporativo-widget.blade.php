<x-filament-widgets::widget>
    <x-filament::card>
        <div class="flex flex-col gap-8">

            {{-- Ícono --}}
            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-primary-50 dark:bg-primary-950">
                <x-filament::icon
                    icon="heroicon-o-envelope"
                    class="h-8 w-8 text-primary-600 dark:text-primary-400"
                />
            </div>

            {{-- Texto --}}
            <div class="flex flex-col gap-3">
                <p class="text-xs font-bold uppercase tracking-widest text-primary-600 dark:text-primary-400">
                    Correo Corporativo
                </p>
                <h3 class="text-2xl font-bold leading-tight text-gray-950 dark:text-white">
                    ¿Necesitas más cuentas de correo?
                </h3>
                <p class="text-base leading-loose text-gray-500 dark:text-gray-400">
                    Tu plan incluye <strong class="font-semibold text-gray-700 dark:text-gray-300">1 correo corporativo</strong>.
                    Si tu equipo crece o necesitas más espacio, tenemos planes con dominio propio, acceso web y soporte incluido.
                </p>
            </div>

            {{-- CTA --}}
            <div class="flex items-center justify-between border-t border-gray-100 pt-6 dark:border-white/10">
                <span class="text-xs text-gray-400 dark:text-gray-500">espacioveloz.com</span>
                <x-filament::button
                    tag="a"
                    href="https://espacioveloz.com/correo-corporativo/"
                    target="_blank"
                    rel="noopener noreferrer"
                    icon="heroicon-o-arrow-top-right-on-square"
                    icon-position="after"
                >
                    Ver planes
                </x-filament::button>
            </div>

        </div>
    </x-filament::card>
</x-filament-widgets::widget>
