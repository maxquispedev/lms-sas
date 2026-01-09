<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        {{-- Breadcrumbs --}}
        <nav class="mb-6" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400">
                <li>
                    <a href="{{ route('student.dashboard') }}" class="hover:text-gray-900 dark:hover:text-gray-100 transition-colors">
                        Inicio
                    </a>
                </li>
                <li>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </li>
                <li>
                    <span class="truncate max-w-xs">{{ $course->title }}</span>
                </li>
                <li>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </li>
                <li class="text-gray-900 dark:text-gray-100 font-medium truncate max-w-xs">
                    {{ $currentLesson->title }}
                </li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left Column: Video Player (2/3) --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Video Player --}}
                <div class="aspect-video bg-gray-900 dark:bg-black rounded-lg overflow-hidden relative shadow-lg">
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

                {{-- Lesson Title and Info --}}
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-4">
                        {{ $currentLesson->title }}
                    </h1>

                    {{-- Instructor Information --}}
                    @if($course->teacher)
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-full bg-gray-300 dark:bg-gray-700 flex items-center justify-center overflow-hidden">
                                @if($course->teacher->avatar_url)
                                    <img src="{{ asset('storage/' . $course->teacher->avatar_url) }}" alt="{{ $course->teacher->name }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-gray-600 dark:text-gray-300 font-medium text-sm">
                                        {{ substr($course->teacher->name, 0, 1) }}
                                    </span>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $course->teacher->name }}
                                </p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">
                                    Creador de Contenido
                                </p>
                            </div>
                        </div>
                    @endif

                    {{-- Course Description --}}
                    @if($currentLesson->content)
                        <div class="mt-6">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-3">
                                {{ $currentLesson->title }}
                            </h2>
                            <div class="prose dark:prose-invert max-w-none text-gray-700 dark:text-gray-300">
                                {!! $currentLesson->content !!}
                            </div>
                        </div>
                    @elseif($course->description)
                        <div class="mt-6">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-3">
                                Sobre este curso
                            </h2>
                            <div class="prose dark:prose-invert max-w-none text-gray-700 dark:text-gray-300">
                                {!! $course->description !!}
                            </div>
                        </div>
                    @endif
                </div>

            </div>

            {{-- Right Column: Content Sidebar (1/3) --}}
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 sticky top-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                            Contenido
                        </h2>
                    </div>

                    {{-- Action Buttons Row --}}
                    <div class="flex items-center justify-between gap-2 mb-6">
                        {{-- Marcar Visto Button (Left) --}}
                        <button
                            wire:click="toggleComplete"
                            class="group flex items-center justify-center gap-2.5 px-4 py-2.5 min-h-[44px] rounded-lg transition-all duration-200 font-medium text-sm shadow-sm {{ $this->isLessonCompleted() 
                                ? 'bg-green-500 dark:bg-green-600 hover:bg-green-600 dark:hover:bg-green-700 text-white shadow-green-500/20 border border-green-600 dark:border-green-500' 
                                : 'bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 hover:border-gray-400 dark:hover:border-gray-500 cursor-pointer' 
                            }}"
                            aria-label="{{ $this->isLessonCompleted() ? 'Marcar como no completado' : 'Marcar como completado' }}"
                        >
                            @if($this->isLessonCompleted())
                                {{-- Check icon when completed --}}
                                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                <span class="whitespace-nowrap">Completado</span>
                            @else
                                {{-- Circle icon when not completed --}}
                                <svg class="w-5 h-5 flex-shrink-0 text-gray-400 dark:text-gray-500 group-hover:text-gray-600 dark:group-hover:text-gray-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="whitespace-nowrap">No Completado</span>
                            @endif
                        </button>

                        {{-- Navigation Buttons (Right) --}}
                        <div class="flex items-center gap-2">
                            {{-- Previous Button --}}
                            @if($previousLesson)
                                <a
                                    href="{{ route('course.learn', [$course, $previousLesson->slug]) }}"
                                    class="flex items-center justify-center w-10 h-10 rounded-lg bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 transition-colors duration-200"
                                    title="Lección anterior"
                                >
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </a>
                            @else
                                <button
                                    disabled
                                    class="flex items-center justify-center w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-600 cursor-not-allowed"
                                    title="No hay lección anterior"
                                >
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>
                            @endif

                            {{-- Next Button --}}
                            @if($nextLesson)
                                <a
                                    href="{{ route('course.learn', [$course, $nextLesson->slug]) }}"
                                    class="flex items-center justify-center w-10 h-10 rounded-lg bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 text-gray-800 dark:text-gray-200 transition-colors duration-200"
                                    title="Siguiente lección"
                                >
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            @else
                                <button
                                    disabled
                                    class="flex items-center justify-center w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-600 cursor-not-allowed"
                                    title="No hay siguiente lección"
                                >
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </div>

                    {{-- Separator --}}
                    <div class="mb-6 border-b border-gray-200 dark:border-gray-700"></div>

                    {{-- Modules and Lessons --}}
                    <div class="space-y-2" x-data="{ 
                        expandedModules: @js([$currentLesson->module_id])
                    }">
                        @forelse($modules as $module)
                            <div class="border-b border-gray-200 dark:border-gray-700 last:border-b-0 pb-2 last:pb-0">
                                {{-- Module Header --}}
                                <button
                                    @click="expandedModules.includes({{ $module->id }}) 
                                        ? expandedModules = expandedModules.filter(id => id !== {{ $module->id }})
                                        : expandedModules.push({{ $module->id }})"
                                    class="w-full flex items-center justify-between py-3 text-left hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg px-2 -mx-2 transition-colors"
                                >
                                    <h3 class="font-semibold text-gray-900 dark:text-gray-100 text-sm">
                                        {{ $module->title }}
                                    </h3>
                                    <svg 
                                        class="w-5 h-5 text-gray-500 dark:text-gray-400 transition-transform duration-200"
                                        :class="expandedModules.includes({{ $module->id }}) ? 'rotate-90' : ''"
                                        fill="none" 
                                        viewBox="0 0 24 24" 
                                        stroke="currentColor"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>

                                {{-- Lessons List --}}
                                <div 
                                    x-show="expandedModules.includes({{ $module->id }})"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                                    x-transition:enter-end="opacity-100 transform translate-y-0"
                                    x-transition:leave="transition ease-in duration-150"
                                    x-transition:leave-start="opacity-100 transform translate-y-0"
                                    x-transition:leave-end="opacity-0 transform -translate-y-2"
                                    class="mt-2 space-y-1"
                                >
                                    @foreach($module->lessons as $lesson)
                                        <a
                                            href="{{ route('course.learn', [$course, $lesson->slug]) }}"
                                            class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors duration-200 group {{ $currentLesson->id === $lesson->id 
                                                ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 font-medium' 
                                                : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' 
                                            }}"
                                        >
                                            {{-- Check Icon if completed --}}
                                            @if(in_array($lesson->id, $completedLessonIds))
                                                <svg class="w-5 h-5 text-green-600 dark:text-green-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            @else
                                                <div class="w-5 h-5 flex-shrink-0 flex items-center justify-center">
                                                    <div class="w-2 h-2 rounded-full bg-gray-400 dark:bg-gray-500"></div>
                                                </div>
                                            @endif

                                            {{-- Lesson Title --}}
                                            <span class="text-sm flex-1 truncate">
                                                {{ $lesson->title }}
                                            </span>

                                            {{-- Duration placeholder (opcional) --}}
                                            {{-- <span class="text-xs text-gray-500 dark:text-gray-400 flex-shrink-0">
                                                18s
                                            </span> --}}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">
                                No hay módulos disponibles.
                            </p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
