<div class="max-w-7xl mx-auto py-8 sm:py-12 px-4 sm:px-6 lg:px-8">
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-gray-100 mb-2">
            Catálogo de Cursos
        </h1>
        <p class="text-sm text-gray-600 dark:text-gray-400">
            Explora todos nuestros cursos disponibles y comienza tu aprendizaje
        </p>
    </div>

    @if($this->courses->isEmpty())
        {{-- Empty State --}}
        <div class="text-center py-16 sm:py-20">
            <div class="max-w-md mx-auto">
                <div class="mx-auto w-24 h-24 sm:w-28 sm:h-28 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-12 h-12 sm:w-14 sm:h-14 text-gray-400 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <h3 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">
                    No hay cursos disponibles
                </h3>
                <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400">
                    Pronto agregaremos nuevos cursos. ¡Vuelve pronto!
                </p>
            </div>
        </div>
    @else
        {{-- Courses Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($this->courses as $course)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md hover:border-blue-300 dark:hover:border-blue-700 transition-all duration-300 group">
                    {{-- Course Image --}}
                    <div class="aspect-video bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-800 overflow-hidden relative">
                        <img 
                            src="{{ $course->cover_url }}" 
                            alt="{{ $course->title }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                            loading="lazy"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        @if($course->is_enrolled)
                            <div class="absolute top-3 right-3">
                                <span class="px-3 py-1 bg-green-500 text-white text-xs font-semibold rounded-full shadow-md">
                                    Inscrito
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- Course Content --}}
                    <div class="p-5 sm:p-6">
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2 line-clamp-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                            {{ $course->title }}
                        </h3>
                        
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
                            {{ $course->description }}
                        </p>
                        
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                <span class="font-medium text-gray-700 dark:text-gray-300">{{ $course->teacher->name ?? 'Sin instructor' }}</span>
                            </p>
                        </div>

                        {{-- Price --}}
                        <div class="mb-5">
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                S/ {{ number_format($course->price, 2) }}
                            </p>
                        </div>

                        {{-- Action Button --}}
                        @if($course->is_enrolled)
                            <a 
                                href="{{ route('course.learn', $course->slug) }}" 
                                class="block w-full text-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md active:scale-[0.98]"
                            >
                                Continuar Aprendiendo
                            </a>
                        @else
                            <a 
                                href="{{ route('course.checkout', $course->slug) }}" 
                                class="block w-full text-center px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 dark:from-blue-500 dark:to-blue-600 dark:hover:from-blue-600 dark:hover:to-blue-700 text-white font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md active:scale-[0.98]"
                            >
                                Comprar Curso
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
