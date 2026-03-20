<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        {{-- Breadcrumbs --}}
        <nav class="mb-6" aria-label="Breadcrumb">
            <ol class="flex items-center flex-wrap gap-2 text-sm">
                <li>
                    <a href="{{ route('student.dashboard') }}" class="text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary transition-colors duration-200 font-medium cursor-pointer">
                        Inicio
                    </a>
                </li>
                <li class="text-gray-400 dark:text-gray-600">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </li>
                <li>
                    <span class="text-gray-600 dark:text-gray-400 truncate max-w-xs">{{ $course->title }}</span>
                </li>
                <li class="text-gray-400 dark:text-gray-600">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </li>
                <li class="text-gray-900 dark:text-gray-100 font-semibold truncate max-w-xs">
                    {{ $currentLesson->title ?? $currentModule?->title }}
                </li>
            </ol>
        </nav>
        
        {{-- Lesson / Module Title --}}
        <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-gray-100 mb-6">
            {{ $currentLesson->title ?? $currentModule?->title }}
        </h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left Column: Video Player (2/3) --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Video Player --}}
                <div class="aspect-video bg-gray-900 dark:bg-black rounded-xl overflow-hidden relative shadow-xl border border-gray-200 dark:border-gray-800">
                    @php
                        $playable = $currentLesson ?? $currentModule;
                    @endphp

                    @if($playable && $playable->iframe_code)
                        <div class="w-full h-full [&>iframe]:w-full [&>iframe]:h-full [&>iframe]:absolute [&>iframe]:inset-0">
                            {!! $playable->iframe_code !!}
                        </div>
                    @else
                        <div class="w-full h-full flex items-center justify-center absolute inset-0 bg-gradient-to-br from-gray-800 to-gray-900 dark:from-black dark:to-gray-900">
                            <div class="text-center px-4">
                                <div class="mx-auto w-20 h-20 bg-gray-700 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-10 h-10 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <p class="text-gray-400 dark:text-gray-500 font-medium">No hay video disponible</p>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Info --}}
                <div>
                    {{-- Course / Lesson / Module Description --}}
                    @if($playable && $playable->content)
                        <div class="mt-6 p-6 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                            <div class="prose prose-sm sm:prose-base dark:prose-invert max-w-none prose-headings:text-gray-900 dark:prose-headings:text-gray-100 prose-p:text-gray-700 dark:prose-p:text-gray-300 prose-a:text-primary dark:prose-a:text-primary prose-strong:text-gray-900 dark:prose-strong:text-gray-100">
                                {!! $playable->content !!}
                            </div>
                        </div>
                    @elseif($course->description)
                        <div class="mt-6 p-6 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4 pb-3 border-b border-gray-200 dark:border-gray-700">
                                Sobre este curso
                            </h2>
                            <div class="prose prose-sm sm:prose-base dark:prose-invert max-w-none prose-headings:text-gray-900 dark:prose-headings:text-gray-100 prose-p:text-gray-700 dark:prose-p:text-gray-300 prose-a:text-primary dark:prose-a:text-primary">
                                {!! $course->description !!}
                            </div>
                        </div>
                    @endif
                </div>

            </div>

            {{-- Right Column: Content Sidebar (1/3) --}}
            <div class="lg:col-span-1 min-w-0">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 sticky top-6 overflow-hidden">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                            Contenido
                        </h2>
                    </div>

                    {{-- Action Buttons Row --}}
                    <div class="flex items-center justify-between gap-2 mb-6">
                        {{-- Marcar Visto Button (Left) --}}
                        <button
                            wire:click="toggleComplete"
                            class="group flex items-center justify-center gap-2.5 px-4 py-2.5 min-h-[44px] rounded-lg transition-all duration-200 font-medium text-sm shadow-sm cursor-pointer {{ $this->isCompleted() 
                                ? 'bg-green-500 dark:bg-green-600 hover:bg-green-600 dark:hover:bg-green-700 text-white shadow-green-500/30 border border-green-600 dark:border-green-500' 
                                : 'bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 hover:border-primary/50 dark:hover:border-primary' 
                            }}"
                            aria-label="{{ $this->isCompleted() ? 'Marcar como no completado' : 'Marcar como completado' }}"
                        >
                            @if($this->isCompleted())
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
                            @if($hasLessons ? $previousLesson : $previousModule)
                                <a
                                    href="{{ route('course.learn', [$course, ($hasLessons ? $previousLesson->slug : $previousModule->slug)]) }}"
                                    class="flex items-center justify-center w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 transition-all duration-200 shadow-sm hover:shadow cursor-pointer"
                                    title="{{ $hasLessons ? 'Lección anterior' : 'Módulo anterior' }}"
                                >
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </a>
                            @else
                                <button
                                    disabled
                                    class="flex items-center justify-center w-10 h-10 rounded-lg bg-gray-50 dark:bg-gray-800 text-gray-300 dark:text-gray-700 cursor-not-allowed"
                                    title="{{ $hasLessons ? 'No hay lección anterior' : 'No hay módulo anterior' }}"
                                >
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>
                            @endif

                            {{-- Next Button --}}
                            @if($hasLessons ? $nextLesson : $nextModule)
                                <a
                                    href="{{ route('course.learn', [$course, ($hasLessons ? $nextLesson->slug : $nextModule->slug)]) }}"
                                    class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary dark:bg-primary hover:bg-primary/90 dark:hover:bg-primary/90 text-white transition-all duration-200 shadow-sm hover:shadow-md active:scale-95 cursor-pointer"
                                    title="{{ $hasLessons ? 'Siguiente lección' : 'Siguiente módulo' }}"
                                >
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            @else
                                <button
                                    disabled
                                    class="flex items-center justify-center w-10 h-10 rounded-lg bg-gray-50 dark:bg-gray-800 text-gray-300 dark:text-gray-700 cursor-not-allowed"
                                    title="{{ $hasLessons ? 'No hay siguiente lección' : 'No hay siguiente módulo' }}"
                                >
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </div>

                    {{-- Download Certificate Button: requiere 100% contenido + exámenes aprobados --}}
                    @if($courseProgress == 100 && $examRequirementMet)
                        <div class="mb-6">
                            <a 
                                href="{{ route('certificates.download', $course) }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="block w-full text-center px-4 py-3 bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 dark:from-yellow-500 dark:to-yellow-600 dark:hover:from-yellow-600 dark:hover:to-yellow-700 text-white font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg active:scale-[0.98] flex items-center justify-center gap-2 cursor-pointer"
                            >
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Descargar Certificado
                            </a>
                        </div>
                    @endif

                    {{-- Separator --}}
                    <div class="mb-6 border-b border-gray-200 dark:border-gray-700"></div>

                    {{-- Modules and Lessons --}}
                    <div class="space-y-2" x-data="{ 
                        expandedModules: @js([$currentLesson->module_id ?? $modules->first()?->id])
                    }">
                        @forelse($modules as $module)
                            <div class="border-b border-gray-200 dark:border-gray-700 last:border-b-0 pb-2 last:pb-0">
                                {{-- Module Header --}}
                                @if($module->lessons->isNotEmpty())
                                    <button
                                        @click="expandedModules.includes({{ $module->id }}) 
                                            ? expandedModules = expandedModules.filter(id => id !== {{ $module->id }})
                                            : expandedModules.push({{ $module->id }})"
                                        class="w-full flex items-center gap-2 py-3 text-left hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg px-2 -mx-2 transition-colors duration-200 group cursor-pointer overflow-hidden"
                                        title="{{ $module->title }}"
                                    >
                                        <span class="font-semibold text-gray-900 dark:text-gray-100 text-sm group-hover:text-primary dark:group-hover:text-primary transition-colors truncate">
                                            {{ $module->title }}
                                        </span>
                                        <svg 
                                            class="w-5 h-5 text-gray-500 dark:text-gray-400 transition-transform duration-200 flex-shrink-0 ml-auto"
                                            :class="expandedModules.includes({{ $module->id }}) ? 'rotate-90 text-primary dark:text-primary' : ''"
                                            fill="none" 
                                            viewBox="0 0 24 24" 
                                            stroke="currentColor"
                                        >
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>
                                @else
                                    <a
                                        href="{{ route('course.learn', [$course, $module->slug]) }}"
                                        class="flex items-center gap-3 py-3 text-left hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg px-2 -mx-2 transition-colors duration-200 group cursor-pointer overflow-hidden {{ ($currentModule && $currentModule->id === $module->id) ? 'bg-primary/10 dark:bg-primary/20 text-secondary dark:text-primary font-semibold border-l-2 border-primary dark:border-primary' : '' }}"
                                        title="{{ $module->title }}"
                                    >
                                        @if(in_array($module->id, $completedModuleIds, true))
                                            <svg class="w-5 h-5 text-green-600 dark:text-green-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        @else
                                            <div class="w-5 h-5 flex-shrink-0 flex items-center justify-center">
                                                <div class="w-2 h-2 rounded-full bg-gray-400 dark:bg-gray-500 group-hover:bg-primary dark:group-hover:bg-primary transition-colors"></div>
                                            </div>
                                        @endif

                                        <span class="font-semibold text-gray-900 dark:text-gray-100 text-sm group-hover:text-primary dark:group-hover:text-primary transition-colors truncate">
                                            {{ $module->title }}
                                        </span>
                                    </a>
                                @endif

                                @if($module->lessons->isNotEmpty())
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
                                                class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group cursor-pointer {{ ($currentLesson && $currentLesson->id === $lesson->id)
                                                    ? 'bg-primary/10 dark:bg-primary/20 text-secondary dark:text-primary font-semibold border-l-2 border-primary dark:border-primary' 
                                                    : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-primary dark:hover:text-primary' 
                                                }}"
                                            >
                                                {{-- Check Icon if completed --}}
                                                @if(in_array($lesson->id, $completedLessonIds, true))
                                                    <svg class="w-5 h-5 text-green-600 dark:text-green-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                @else
                                                    <div class="w-5 h-5 flex-shrink-0 flex items-center justify-center">
                                                        <div class="w-2 h-2 rounded-full bg-gray-400 dark:bg-gray-500 group-hover:bg-primary dark:group-hover:bg-primary transition-colors"></div>
                                                    </div>
                                                @endif

                                                {{-- Lesson Title --}}
                                                <span class="text-sm flex-1 truncate">
                                                    {{ $lesson->title }}
                                                </span>
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">
                                No hay módulos disponibles.
                            </p>
                        @endforelse

                        {{-- Único acceso a exámenes: siempre al final del índice (misma info que antes evitaba el bloque superior) --}}
                        @if($publishedExamsCount > 0)
                            <div class="pt-3 mt-2 border-t border-gray-200 dark:border-gray-700">
                                <a
                                    href="{{ route('course.exams', $course) }}"
                                    wire:navigate
                                    class="flex flex-col gap-1.5 rounded-lg px-3 py-3 -mx-1 border border-gray-200/80 dark:border-gray-600/80 hover:border-primary/40 dark:hover:border-primary/50 hover:bg-primary/5 dark:hover:bg-primary/10 transition-all duration-200 cursor-pointer text-left w-full"
                                >
                                    <div class="flex items-center gap-3 min-w-0">
                                        @if($examRequirementMet)
                                            <svg class="w-5 h-5 text-green-600 dark:text-green-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5 text-primary flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z" />
                                            </svg>
                                        @endif
                                        <span class="text-sm font-semibold text-gray-900 dark:text-gray-100 flex-1 truncate">
                                            Exámenes del curso
                                        </span>
                                        <span class="text-xs font-bold tabular-nums text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-700/80 px-2 py-0.5 rounded-md flex-shrink-0">
                                            {{ $passedExamsCount }}/{{ $publishedExamsCount }}
                                        </span>
                                    </div>
                                    @if($courseProgress == 100 && !$examRequirementMet)
                                        <p class="text-xs text-gray-600 dark:text-gray-400 leading-snug pl-8">
                                            Para descargar el certificado, aprueba todos los exámenes.
                                        </p>
                                    @endif
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
