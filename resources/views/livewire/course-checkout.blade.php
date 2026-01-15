@assets
<script src="https://js.culqi.com/checkout-js"></script>
@endassets

<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        {{-- Left Column: Course Summary --}}
        <div class="space-y-6">
            {{-- Course Image --}}
            <div class="aspect-video bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden">
                @if($course->image_url)
                    <img 
                        src="{{ str_starts_with($course->image_url, 'http') ? $course->image_url : \Illuminate\Support\Facades\Storage::disk('public')->url($course->image_url) }}" 
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
                        S/ {{ number_format($course->price, 2, '.', ',') }}
                    </span>
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        PEN
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
            {{-- Error Message --}}
            @if(session('error'))
                <div class="bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 dark:border-red-400 text-red-800 dark:text-red-200 px-4 py-3 rounded-r-lg shadow-sm">
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <p class="font-medium">Error en el pago</p>
                            <p class="text-sm mt-1">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Success Message --}}
            @if(session('message'))
                <div class="bg-green-50 dark:bg-green-900/30 border-l-4 border-green-500 dark:border-green-400 text-green-800 dark:text-green-200 px-4 py-3 rounded-r-lg shadow-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="font-medium">{{ session('message') }}</span>
                    </div>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 sticky top-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">
                    Finalizar Compra
                </h2>

                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    Estás a un paso. Completa tu pago de forma segura con Culqi.
                </p>

                {{-- Registration Form for Guests --}}
                @guest
                    <div class="space-y-4 mb-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Nombre
                                </label>
                                <input
                                    type="text"
                                    id="first_name"
                                    wire:model="first_name"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                    placeholder="Ingresa tu nombre"
                                >
                                @error('first_name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Apellido
                                </label>
                                <input
                                    type="text"
                                    id="last_name"
                                    wire:model="last_name"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                    placeholder="Ingresa tu apellido"
                                >
                                @error('last_name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Correo electrónico
                            </label>
                            <input
                                type="email"
                                id="email"
                                wire:model="email"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                placeholder="tu@correo.com"
                            >
                            @error('email')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Contraseña
                            </label>
                            <input
                                type="password"
                                id="password"
                                wire:model="password"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                placeholder="Mínimo 8 caracteres"
                            >
                            @error('password')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                @endguest

                {{-- Culqi Payment Button --}}
                <button
                    id="btn_pagar"
                    type="button"
                    wire:click="validateAndPay"
                    wire:loading.attr="disabled"
                    class="block w-full px-6 py-4 bg-purple-600 hover:bg-purple-700 dark:bg-purple-500 dark:hover:bg-purple-600 text-white font-semibold rounded-lg transition-colors duration-200 text-center shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer"
                >
                    <div class="flex items-center justify-center gap-3">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" wire:loading.remove wire:target="validateAndPay">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                        <svg class="animate-spin h-6 w-6 text-white" wire:loading wire:target="validateAndPay" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="validateAndPay">Pagar con Tarjeta/Yape</span>
                        <span wire:loading wire:target="validateAndPay">Procesando pago...</span>
                    </div>
                </button>

                <p class="text-xs text-gray-500 dark:text-gray-400 mt-4 text-center">
                    Pago seguro procesado por Culqi
                </p>
            </div>
        </div>
    </div>
</div>

@script
<script>
(function() {
    // Variable para guardar la instancia del Checkout
    let CulqiInstance = null;

    // Esperamos el evento de Livewire para iniciar el pago
    $wire.on('init-payment', () => {
        // Verificar que CulqiCheckout esté disponible
        if (typeof CulqiCheckout === 'undefined') {
            console.error('La librería de Culqi aún no ha cargado.');
            alert('Por favor, espera unos segundos y vuelve a intentar. La pasarela está cargando.');
            return;
        }

        // Obtener email (guest o autenticado)
        const email = $wire.email || '{{ auth()->user()->email ?? "" }}';

        // Configuración
        const settings = {
            title: '{{ addslashes($course->title) }}',
            currency: 'PEN',
            amount: {{ intval($course->price * 100) }},
        };

        const client = {
            email: email,
        };

        const paymentMethods = {
            tarjeta: true,
            yape: false,
            billetera: false,
            bancaMovil: false,
            agente: false,
            cuotealo: false,
        };

        const options = {
            lang: 'es',
            installments: false,
            modal: true,
            paymentMethods: paymentMethods,
            paymentMethodsSort: Object.keys(paymentMethods),
        };

        const appearance = {
            theme: 'default',
            hiddenCulqiLogo: false,
            menuType: 'sidebar',
        };

        const config = {
            settings,
            client,
            options,
            appearance,
        };

        const publicKey = 'pk_test_1Ejteu5U8jSpW630';

        // Crear nueva instancia cada vez (evita problemas de estado)
        CulqiInstance = new CulqiCheckout(publicKey, config);

        // Handler para cuando Culqi responde
        CulqiInstance.culqi = function() {
            if (CulqiInstance.token) {
                const tokenId = CulqiInstance.token.id;
                const tokenEmail = CulqiInstance.token.email;
                console.log('Token creado:', tokenId);
                CulqiInstance.close();
                $wire.processPayment(tokenId, tokenEmail);
            } else if (CulqiInstance.order) {
                console.log('Order creada:', CulqiInstance.order);
                CulqiInstance.close();
            } else if (CulqiInstance.error) {
                console.error('Error Culqi:', CulqiInstance.error);
                alert(CulqiInstance.error.user_message || 'Ocurrió un error en el pago.');
            }
        };

        // Abrir el checkout
        CulqiInstance.open();
    });
})();
</script>
@endscript
