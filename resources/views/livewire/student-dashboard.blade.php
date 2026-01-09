<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    {{-- Flash Message --}}
    @if(session('message'))
        <div class="mb-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-200 px-4 py-3 rounded-lg">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('message') }}</span>
            </div>
        </div>
    @endif

    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-8">
        Mis Cursos
    </h1>

    @if($this->courses->isEmpty())
        <div class="text-center py-12">
            <div class="max-w-md mx-auto">
                <svg class="mx-auto h-24 w-24 text-gray-400 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-gray-100">
                    Aún no te has inscrito en ningún curso
                </h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Explora nuestros cursos disponibles y comienza tu aprendizaje hoy.
                </p>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($this->courses as $course)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    {{-- Course Image --}}
                    <div class="aspect-video bg-gray-200 dark:bg-gray-700 overflow-hidden">
                        <img 
                            src="{{ $course->cover_url }}" 
                            alt="{{ $course->title }}"
                            class="w-full h-full object-cover"
                            loading="lazy"
                        >
                    </div>

                    {{-- Course Content --}}
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2 line-clamp-2">
                            {{ $course->title }}
                        </h3>
                        
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Instructor: <span class="font-medium">{{ $course->teacher->name ?? 'Sin instructor' }}</span>
                        </p>

                        {{-- Progress Bar --}}
                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-xs font-medium text-gray-700 dark:text-gray-300">
                                    Progreso
                                </span>
                                <span class="text-xs font-medium text-gray-700 dark:text-gray-300">
                                    {{ $course->progress }}% Completado
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div 
                                    class="bg-blue-600 dark:bg-blue-500 h-2 rounded-full transition-all duration-300"
                                    style="width: {{ $course->progress }}%"
                                ></div>
                            </div>
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                {{ $course->completed_lessons }} / {{ $course->total_lessons }} lecciones completadas
                            </p>
                        </div>

                        {{-- Action Button --}}
                        <a 
                            href="{{ route('course.learn', $course->slug) }}" 
                            class="block w-full text-center px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white font-medium rounded-lg transition-colors duration-200"
                        >
                            Continuar Aprendiendo
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

