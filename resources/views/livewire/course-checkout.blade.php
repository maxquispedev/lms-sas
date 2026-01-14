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
                    Estás a un paso. Completa tu pago de forma segura con Culqi.
                </p>

                {{-- Culqi Payment Button --}}
                <button
                    id="btn_pagar"
                    type="button"
                    class="block w-full px-6 py-4 bg-purple-600 hover:bg-purple-700 dark:bg-purple-500 dark:hover:bg-purple-600 text-white font-semibold rounded-lg transition-colors duration-200 text-center shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
                    wire:loading.attr="disabled"
                >
                    <div class="flex items-center justify-center gap-3">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" wire:loading.remove wire:target="processPayment">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                        <svg class="animate-spin h-6 w-6 text-white" wire:loading wire:target="processPayment" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="processPayment">Pagar con Tarjeta/Yape</span>
                        <span wire:loading wire:target="processPayment">Procesando pago...</span>
                    </div>
                </button>

                <p class="text-xs text-gray-500 dark:text-gray-400 mt-4 text-center">
                    Pago seguro procesado por Culqi
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

{{-- Culqi Checkout Script --}}
<script src="https://js.culqi.com/checkout-js"></script>

@script
<script>
    // Configurar Culqi con las variables del componente
    Culqi.publicKey = '{{ config('services.culqi.public_key') }}';

    // Configurar settings de Culqi
    Culqi.settings({
        title: '{{ $course->title }}',
        currency: 'PEN',
        amount: {{ (int)($course->price * 100) }}, // Monto en céntimos (multiplicado por 100)
        description: 'Compra del curso: {{ $course->title }}',
    });

    // Configurar opciones de Culqi
    Culqi.options({
        lang: 'es',
        paymentMethods: {
            tarjeta: true,
            yape: true,
            bancaMovil: false,
            agente: false,
            billetera: false,
            cuotealo: false,
        },
        appearance: {
            style: 'default',
        },
    });

    // Evento click del botón de pago
    document.getElementById('btn_pagar').addEventListener('click', function(e) {
        e.preventDefault();
        Culqi.open();
    });

    // Función callback de Culqi (debe ser global)
    window.culqi = function() {
        if (Culqi.token) {
            // Token generado exitosamente
            const tokenId = Culqi.token.id;
            const email = Culqi.token.email;
            
            // Enviar token al componente Livewire
            $wire.processPayment(tokenId, email);
        } else if (Culqi.error) {
            // Error en el proceso de pago
            alert(Culqi.error.user_message || 'Ocurrió un error al procesar el pago. Por favor, intenta nuevamente.');
        }
    };
</script>
@endscript
