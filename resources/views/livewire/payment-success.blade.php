<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl w-full">
        {{-- Success Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 md:p-12">
            {{-- Success Icon --}}
            <div class="flex justify-center mb-6">
                <div class="relative">
                    {{-- Animated Check Circle --}}
                    <div class="w-24 h-24 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center animate-pulse">
                        <svg class="w-16 h-16 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    {{-- Outer Ring Animation --}}
                    <div class="absolute inset-0 w-24 h-24 border-4 border-green-500 rounded-full animate-ping opacity-20"></div>
                </div>
            </div>

            {{-- Title --}}
            <h1 class="text-3xl md:text-4xl font-bold text-center text-gray-900 dark:text-gray-100 mb-4">
                ¡Pago exitoso!
            </h1>

            {{-- Success Message --}}
            <p class="text-lg text-center text-gray-600 dark:text-gray-400 mb-8">
                Tu compra se ha procesado correctamente. Ya tienes acceso al curso
                <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $course->title }}</span>.
            </p>

            {{-- Order Summary --}}
            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-6 mb-8 space-y-4">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    Resumen de tu compra
                </h2>

                {{-- Course Name --}}
                <div class="flex items-start justify-between py-3 border-b border-gray-200 dark:border-gray-600">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                            Curso
                        </p>
                        <p class="text-base font-semibold text-gray-900 dark:text-gray-100">
                            {{ $course->title }}
                        </p>
                    </div>
                </div>

                {{-- Price Paid --}}
                <div class="flex items-start justify-between py-3 border-b border-gray-200 dark:border-gray-600">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                            Precio pagado
                        </p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                            ${{ number_format($order?->total_amount ?? $course->price, 2, '.', ',') }}
                            <span class="text-sm font-normal text-gray-500 dark:text-gray-400">USD</span>
                        </p>
                    </div>
                </div>

                {{-- Purchase Date --}}
                <div class="flex items-start justify-between py-3">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                            Fecha de compra
                        </p>
                        <p class="text-base font-semibold text-gray-900 dark:text-gray-100">
                            {{ $order?->created_at?->format('d/m/Y H:i') ?? now()->format('d/m/Y H:i') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row gap-4">
                {{-- Primary Button: Go to Course --}}
                <a
                    href="{{ route('course.learn', $course) }}"
                    class="flex-1 px-6 py-4 bg-purple-600 hover:bg-purple-700 dark:bg-purple-500 dark:hover:bg-purple-600 text-white font-semibold rounded-lg transition-colors duration-200 text-center shadow-lg hover:shadow-xl"
                >
                    <div class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Ir al curso</span>
                    </div>
                </a>

                {{-- Secondary Button: View My Courses --}}
                <a
                    href="{{ route('student.dashboard') }}"
                    class="flex-1 px-6 py-4 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-900 dark:text-gray-100 font-semibold rounded-lg transition-colors duration-200 text-center"
                >
                    <div class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        <span>Ver mis cursos</span>
                    </div>
                </a>
            </div>
        </div>

        {{-- Additional Info --}}
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                ¿Necesitas ayuda? 
                <a href="#" class="text-purple-600 dark:text-purple-400 hover:underline font-medium">
                    Contacta con soporte
                </a>
            </p>
        </div>
    </div>
</div>
