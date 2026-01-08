<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        {{-- Left Column: Course Summary --}}
        <div class="space-y-6">
            {{-- Course Image --}}
            <div class="aspect-video bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden">
                @if($course->image_url)
                    <img 
                        src="{{ $course->image_url }}" 
                        alt="{{ $course->title }}"
                        class="w-full h-full object-cover"
                    >
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-24 h-24 text-gray-400 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                @endif
            </div>

            {{-- Course Title and Price --}}
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-4">
                    {{ $course->title }}
                </h1>
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-bold text-gray-900 dark:text-gray-100">
                        ${{ number_format($course->price, 2, '.', ',') }}
                    </span>
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        USD
                    </span>
                </div>
            </div>

            {{-- Benefits List --}}
            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    Lo que obtienes:
                </h2>
                <ul class="space-y-3">
                    <li class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-gray-700 dark:text-gray-300">
                            Acceso de por vida
                        </span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-gray-700 dark:text-gray-300">
                            Certificado de finalización
                        </span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-gray-700 dark:text-gray-300">
                            Acceso a todas las lecciones
                        </span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-gray-700 dark:text-gray-300">
                            Soporte y actualizaciones
                        </span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-gray-700 dark:text-gray-300">
                            Materiales descargables
                        </span>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Right Column: Payment --}}
        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 sticky top-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">
                    Finalizar Compra
                </h2>

                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    Estás a un paso. Como estamos en lanzamiento, el pago se verifica manualmente.
                </p>

                {{-- WhatsApp Payment Button --}}
                <a
                    href="https://wa.me/XXXXXXXXX?text={{ urlencode("Hola, quiero comprar el curso {$course->title} por $" . number_format($course->price, 2, '.', ',') . " USD") }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="block w-full px-6 py-4 bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 text-white font-semibold rounded-lg transition-colors duration-200 text-center shadow-lg hover:shadow-xl"
                >
                    <div class="flex items-center justify-center gap-3">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                        <span>Pagar ${{ number_format($course->price, 2, '.', ',') }} vía WhatsApp</span>
                    </div>
                </a>

                <p class="text-xs text-gray-500 dark:text-gray-400 mt-4 text-center">
                    Al hacer clic, se abrirá WhatsApp para completar tu compra
                </p>
            </div>

            {{-- Additional Info --}}
            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="text-sm text-blue-800 dark:text-blue-200">
                        <p class="font-medium mb-1">Proceso de pago manual</p>
                        <p>Una vez que completes el pago, recibirás acceso al curso en un plazo máximo de 24 horas.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

