@assets
<script src="https://js.culqi.com/checkout-js"></script>
@endassets

<div>
{{-- Top security bar --}}
<div class="bg-secondary text-white py-2.5 px-4 text-center text-xs font-medium tracking-wide">
    <span class="inline-flex items-center gap-1.5">
        <svg class="w-3.5 h-3.5 text-accent" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
        </svg>
        Pago 100% seguro &nbsp;·&nbsp; Encriptación SSL &nbsp;·&nbsp; Acceso inmediato tras la compra
    </span>
</div>

<div class="min-h-screen bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 py-10 lg:py-14">

        {{-- Session messages --}}
        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-5 py-4 rounded-xl shadow-sm">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="font-semibold">Error en el pago</p>
                        <p class="text-sm mt-0.5 opacity-90">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('message'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-5 py-4 rounded-xl shadow-sm">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-semibold">{{ session('message') }}</span>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">

            {{-- LEFT: Course value proposition --}}
            <div class="lg:col-span-7 space-y-6">

                {{-- Course image --}}
                <div class="relative rounded-2xl overflow-hidden shadow-xl aspect-video bg-gray-200">
                    @if($course->image_url)
                        <img
                            src="{{ str_starts_with($course->image_url, 'http') ? $course->image_url : \Illuminate\Support\Facades\Storage::disk('public')->url($course->image_url) }}"
                            alt="{{ $course->title }}"
                            class="w-full h-full object-cover"
                        >
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary/20 to-secondary/20">
                            <svg class="w-20 h-20 text-primary/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                    @endif
                    <div class="absolute top-4 left-4">
                        <span class="inline-flex items-center gap-1.5 bg-accent text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg uppercase tracking-wide">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                            </svg>
                            Acceso inmediato
                        </span>
                    </div>
                </div>

                {{-- Title + Price --}}
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 leading-tight">
                        {{ $course->title }}
                    </h1>
                    <div class="flex items-baseline gap-3 mt-3 flex-wrap">
                        @if($course->hasSalePrice())
                            <span class="text-lg text-gray-400 line-through">S/ {{ number_format((float) $course->price, 2, '.', ',') }}</span>
                            <span class="text-4xl font-extrabold text-primary">S/ {{ number_format((float) $course->sale_price, 2, '.', ',') }}</span>
                            <span class="bg-accent/10 text-accent text-sm font-bold px-2.5 py-1 rounded-full">
                                Oferta especial
                            </span>
                        @else
                            <span class="text-4xl font-extrabold text-primary">S/ {{ number_format($course->price, 2, '.', ',') }}</span>
                        @endif
                        <span class="text-sm text-gray-400">PEN · Pago único</span>
                    </div>
                </div>

                {{-- What you get --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                    <h2 class="text-base font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <span class="w-6 h-6 bg-primary/10 rounded-full flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        Todo lo que obtienes al inscribirte hoy
                    </h2>
                    <ul class="space-y-3">
                        @php
                            $benefits = [
                                $course->access_text,
                                'Certificado de finalización',
                                'Acceso a todas las lecciones y videos',
                                'Materiales y recursos descargables',
                                'Soporte y actualizaciones incluidas',
                            ];
                        @endphp
                        @foreach($benefits as $benefit)
                            <li class="flex items-start gap-3">
                                <span class="w-5 h-5 bg-primary rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                </span>
                                <span class="text-gray-700 text-sm leading-relaxed">{{ $benefit }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Guarantee --}}
                <div class="flex items-start gap-4 bg-green-50 border border-green-100 rounded-2xl p-5">
                    <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-green-800 text-sm">Compra protegida</p>
                        <p class="text-green-700 text-xs mt-1 leading-relaxed">Tu pago está protegido con encriptación de 256 bits. Tus datos financieros nunca son almacenados en nuestros servidores.</p>
                    </div>
                </div>

            </div>

            {{-- RIGHT: Payment panel --}}
            <div class="lg:col-span-5">
                <div class="sticky top-6">

                    {{-- Order summary card --}}
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">

                        {{-- Card header --}}
                        <div class="bg-secondary px-6 py-4">
                            <p class="text-white font-bold text-base">Resumen de tu pedido</p>
                            <p class="text-gray-400 text-xs mt-0.5">Revisa los detalles antes de pagar</p>
                        </div>

                        <div class="p-6 space-y-5">

                            {{-- Order detail --}}
                            <div class="flex items-start gap-3 pb-4 border-b border-gray-100">
                                <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-800 leading-tight line-clamp-2">{{ $course->title }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $course->access_text }}</p>
                                </div>
                            </div>

                            {{-- Price breakdown --}}
                            <div class="space-y-2">
                                @if($course->hasSalePrice())
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Precio regular</span>
                                        <span class="text-gray-400 line-through">S/ {{ number_format((float) $course->price, 2, '.', ',') }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-green-600 font-medium">Descuento especial</span>
                                        <span class="text-green-600 font-medium">
                                            - S/ {{ number_format((float) $course->price - (float) $course->sale_price, 2, '.', ',') }}
                                        </span>
                                    </div>
                                @endif
                                <div class="flex justify-between items-baseline pt-2 border-t border-gray-100">
                                    <span class="text-gray-700 font-bold">Total a pagar</span>
                                    <span class="text-2xl font-extrabold text-primary">
                                        S/ {{ number_format((float) $course->effective_price, 2, '.', ',') }}
                                    </span>
                                </div>
                            </div>

                            {{-- Registration Form for Guests --}}
                            @guest
                                <div class="pt-2 space-y-4 border-t border-gray-100">
                                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Crea tu cuenta para acceder al curso</p>

                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label for="first_name" class="block text-xs font-semibold text-gray-600 mb-1.5">
                                                Nombre
                                            </label>
                                            <input
                                                type="text"
                                                id="first_name"
                                                wire:model="first_name"
                                                class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary/30 focus:border-primary bg-white text-gray-900 placeholder-gray-400 transition"
                                                placeholder="Tu nombre"
                                            >
                                            @error('first_name')
                                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="last_name" class="block text-xs font-semibold text-gray-600 mb-1.5">
                                                Apellido
                                            </label>
                                            <input
                                                type="text"
                                                id="last_name"
                                                wire:model="last_name"
                                                class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary/30 focus:border-primary bg-white text-gray-900 placeholder-gray-400 transition"
                                                placeholder="Tu apellido"
                                            >
                                            @error('last_name')
                                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div>
                                        <label for="email" class="block text-xs font-semibold text-gray-600 mb-1.5">
                                            Correo electrónico
                                        </label>
                                        <input
                                            type="email"
                                            id="email"
                                            wire:model="email"
                                            class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary/30 focus:border-primary bg-white text-gray-900 placeholder-gray-400 transition"
                                            placeholder="tu@correo.com"
                                        >
                                        @error('email')
                                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="password" class="block text-xs font-semibold text-gray-600 mb-1.5">
                                            Contraseña de acceso
                                        </label>
                                        <input
                                            type="password"
                                            id="password"
                                            wire:model="password"
                                            class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary/30 focus:border-primary bg-white text-gray-900 placeholder-gray-400 transition"
                                            placeholder="Mínimo 8 caracteres"
                                        >
                                        @error('password')
                                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            @endguest

                            {{-- CTA Button --}}
                            <button
                                id="btn_pagar"
                                type="button"
                                wire:click="validateAndPay"
                                wire:loading.attr="disabled"
                                class="relative w-full py-4 px-6 bg-primary hover:bg-primary/90 active:scale-[0.98] text-white font-bold text-base rounded-xl transition-all duration-150 shadow-lg shadow-primary/30 hover:shadow-primary/40 disabled:opacity-60 disabled:cursor-not-allowed cursor-pointer"
                            >
                                <span wire:loading.remove wire:target="validateAndPay" class="flex items-center justify-center gap-2.5">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                                        <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd" />
                                    </svg>
                                    Pagar con Tarjeta — S/ {{ number_format((float) $course->effective_price, 2, '.', ',') }}
                                </span>
                                <span wire:loading wire:target="validateAndPay" class="flex items-center justify-center gap-2.5">
                                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Procesando pago...
                                </span>
                            </button>

                            {{-- Security row --}}
                            <div class="flex items-center justify-center gap-3 pt-1">
                                <div class="flex items-center gap-1 text-gray-400">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-xs">SSL 256-bit</span>
                                </div>
                                <span class="text-gray-200">|</span>
                                <div class="flex items-center gap-1 text-gray-400">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-xs">Pago seguro</span>
                                </div>
                                <span class="text-gray-200">|</span>
                                <span class="text-xs text-gray-400 font-medium">Culqi</span>
                            </div>

                        </div>
                    </div>

                    {{-- Reassurance below card --}}
                    <div class="mt-4 grid grid-cols-3 gap-3 text-center">
                        <div class="bg-white rounded-xl p-3 border border-gray-100">
                            <svg class="w-5 h-5 text-primary mx-auto mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            <p class="text-xs text-gray-500 font-medium leading-tight">Acceso<br>inmediato</p>
                        </div>
                        <div class="bg-white rounded-xl p-3 border border-gray-100">
                            <svg class="w-5 h-5 text-primary mx-auto mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            <p class="text-xs text-gray-500 font-medium leading-tight">Desde cualquier<br>dispositivo</p>
                        </div>
                        <div class="bg-white rounded-xl p-3 border border-gray-100">
                            <svg class="w-5 h-5 text-primary mx-auto mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <p class="text-xs text-gray-500 font-medium leading-tight">Soporte<br>incluido</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
</div>

@script
<script>
(function() {
    let CulqiInstance = null;

    $wire.on('init-payment', () => {
        if (typeof CulqiCheckout === 'undefined') {
            console.error('La librería de Culqi aún no ha cargado.');
            alert('Por favor, espera unos segundos y vuelve a intentar. La pasarela está cargando.');
            return;
        }

        const email = $wire.email || '{{ auth()->user()->email ?? "" }}';

        const settings = {
            title: '{{ addslashes($course->title) }}',
            currency: 'PEN',
            amount: {{ intval($course->effective_price * 100) }},
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

        const publicKey = @json(config('services.culqi.public_key'));

        CulqiInstance = new CulqiCheckout(publicKey, config);

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

        CulqiInstance.open();
    });
})();
</script>
@endscript
