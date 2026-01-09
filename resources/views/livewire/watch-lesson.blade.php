<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Left Column: Player (2/3) --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Lesson Title --}}
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                    {{ $currentLesson->title }}
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Curso: <span class="font-medium">{{ $course->title }}</span>
                </p>
            </div>

            {{-- Video Player --}}
            <div class="aspect-video bg-gray-900 dark:bg-black rounded-lg overflow-hidden relative">
                @if($currentLesson->iframe_code)
                    <div class="w-full h-full [&>iframe]:w-full [&>iframe]:h-full [&>iframe]:absolute [&>iframe]:inset-0">
                        {!! $currentLesson->iframe_code !!}
                    </div>
                @else
                    <div class="w-full h-full flex items-center justify-center absolute inset-0">
                        <div class="text-center">
                            <svg class="mx-auto h-16 w-16 text-gray-400 dark:text-gray-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            <p class="text-gray-400 dark:text-gray-600">No hay video disponible</p>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Lesson Content --}}
            @if($currentLesson->content)
                <div class="mt-8 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                        Sobre esta lección
                    </h3>
                    <div class="prose dark:prose-invert max-w-none">
                        {!! $currentLesson->content !!}
                    </div>
                </div>
            @endif

            {{-- Controls --}}
            <div class="flex flex-col sm:flex-row gap-4">
                <button
                    wire:click="toggleComplete"
                    class="flex-1 px-6 py-3 rounded-lg font-medium transition-colors duration-200 {{ $this->isLessonCompleted() 
                        ? 'bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 text-white' 
                        : 'bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-900 dark:text-gray-100' 
                    }}"
                >
                    <div class="flex items-center justify-center gap-2">
                        @if($this->isLessonCompleted())
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Marcar como No Visto</span>
                        @else
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Marcar como Visto</span>
                        @endif
                    </div>
                </button>

                @if($nextLesson)
                    <a
                        href="{{ route('course.learn', [$course, $nextLesson->slug]) }}"
                        class="flex-1 px-6 py-3 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white rounded-lg font-medium transition-colors duration-200 text-center"
                    >
                        <div class="flex items-center justify-center gap-2">
                            <span>Siguiente Lección</span>
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </a>
                @endif
            </div>
        </div>

        {{-- Right Column: Playlist (1/3) --}}
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 sticky top-8">
                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">
                    Temario
                </h2>

                <div class="space-y-4">
                    @forelse($modules as $module)
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                {{ $module->title }}
                            </h3>
                            <ul class="space-y-1">
                                @foreach($module->lessons as $lesson)
                                    <li>
                                        <a
                                            href="{{ route('course.learn', [$course, $lesson->slug]) }}"
                                            class="flex items-center gap-2 px-3 py-2 rounded-lg transition-colors duration-200 {{ $currentLesson->id === $lesson->id 
                                                ? 'bg-blue-100 dark:bg-blue-900 text-blue-900 dark:text-blue-100 font-medium' 
                                                : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' 
                                            }}"
                                        >
                                            {{-- Check Icon if completed --}}
                                            @if(in_array($lesson->id, $completedLessonIds))
                                                <svg class="w-5 h-5 text-green-600 dark:text-green-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            @else
                                                <div class="w-5 h-5 flex-shrink-0"></div>
                                            @endif

                                            {{-- Lesson Title --}}
                                            <span class="text-sm flex-1 truncate">
                                                {{ $lesson->title }}
                                            </span>

                                            {{-- Current Lesson Indicator --}}
                                            @if($currentLesson->id === $lesson->id)
                                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                                                </svg>
                                            @endif
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            No hay módulos disponibles.
                        </p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

