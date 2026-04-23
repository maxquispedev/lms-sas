<x-filament-widgets::widget>
    <x-filament::card>
        <div class="flex flex-col gap-8">

            {{-- Ícono --}}
            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gray-100 dark:bg-white/5">
                <x-filament::icon
                    icon="heroicon-o-rocket-launch"
                    class="h-8 w-8 text-primary-600 dark:text-primary-400"
                />
            </div>

            {{-- Texto --}}
            <div class="flex flex-col gap-3">
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">
                    Espacio Veloz
                </p>
                <h3 class="text-2xl font-bold leading-tight text-gray-950 dark:text-white">
                    Tu socio tecnológico
                </h3>
                <p class="text-base leading-loose text-gray-500 dark:text-gray-400">
                    Servidores, dominios, Desarrolos a medida.
                    Todo lo que necesitas para crecer en línea, en un solo lugar y con respaldo profesional.
                </p>
            </div>

            {{-- CTA --}}
            <div class="flex items-center justify-between border-t border-gray-100 pt-6 dark:border-white/10">
                <x-filament::button
                    tag="a"
                    href="https://espacioveloz.com/"
                    target="_blank"
                    rel="noopener noreferrer"
                    icon="heroicon-o-arrow-top-right-on-square"
                    icon-position="after"
                    color="gray"
                >
                    Conocer más
                </x-filament::button>
            </div>

        </div>
    </x-filament::card>
</x-filament-widgets::widget>
