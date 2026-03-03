<div class="max-w-7xl mx-auto py-8 sm:py-12 px-4 sm:px-6 lg:px-8">
    {{-- Flash Messages --}}
    @if(session('message'))
        <div class="mb-6 bg-primary/10 dark:bg-primary/20 border-l-4 border-primary dark:border-primary text-secondary dark:text-primary px-4 py-3 rounded-r-lg shadow-sm">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-medium">{{ session('message') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 dark:border-red-400 text-red-800 dark:text-red-200 px-4 py-3 rounded-r-lg shadow-sm">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-gray-100 mb-2">
            Mis Cursos
        </h1>
        <p class="text-sm text-gray-600 dark:text-gray-400">
            Gestiona tu progreso y continúa aprendiendo
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
                    Aún no te has inscrito en ningún curso
                </h3>
                <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 mb-6">
                    Explora nuestros cursos disponibles y comienza tu aprendizaje hoy.
                </p>
            </div>
        </div>
    @else
        {{-- Courses Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($this->courses as $course)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md hover:border-primary/50 dark:hover:border-primary transition-all duration-300 group">
                    {{-- Course Image --}}
                    <div class="aspect-video bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-800 overflow-hidden relative">
                        <img 
                            src="{{ $course->cover_url }}" 
                            alt="{{ $course->title }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                            loading="lazy"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>

                    {{-- Course Content --}}
                    <div class="p-5 sm:p-6">
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2 line-clamp-2 group-hover:text-primary dark:group-hover:text-primary transition-colors">
                            {{ $course->title }}
                        </h3>
                        
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                <span class="font-medium text-gray-700 dark:text-gray-300">{{ $course->teacher->name ?? 'Sin instructor' }}</span>
                            </p>
                        </div>

                        {{-- Progress Bar --}}
                        <div class="mb-5">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">
                                    Progreso
                                </span>
                                <span class="text-xs font-semibold text-primary dark:text-primary">
                                    {{ $course->progress }}%
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 overflow-hidden">
                                <div 
                                    class="bg-gradient-to-r from-primary to-primary/90 dark:from-primary dark:to-primary/90 h-2.5 rounded-full transition-all duration-500 shadow-sm"
                                    style="width: {{ $course->progress }}%"
                                ></div>
                            </div>
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                {{ $course->completed_progress_items ?? 0 }} de {{ $course->total_progress_items ?? 0 }} {{ $course->progress_total_label ?? 'lecciones' }} completados
                            </p>
                        </div>

                        {{-- Download Certificate Button (only when 100% complete) --}}
                        @if($course->progress == 100)
                            <a 
                                href="{{ route('certificates.download', $course) }}" 
                                class="block w-full text-center px-4 py-2.5 bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 dark:from-yellow-500 dark:to-yellow-600 dark:hover:from-yellow-600 dark:hover:to-yellow-700 text-white font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg active:scale-[0.98] mb-3 flex items-center justify-center gap-2 cursor-pointer"
                            >
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Descargar Certificado
                            </a>
                        @endif

                        {{-- Action Button --}}
                        <a 
                            href="{{ route('course.learn', $course->slug) }}" 
                            class="block w-full text-center px-4 py-2.5 bg-primary hover:bg-primary/90 dark:bg-primary dark:hover:bg-primary/90 text-white font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md active:scale-[0.98] cursor-pointer"
                        >
                            Continuar Aprendiendo
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

