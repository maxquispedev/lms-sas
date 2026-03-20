<div class="max-w-3xl mx-auto py-8 sm:py-12 px-4 sm:px-6 lg:px-8">
    {{-- Encabezado --}}
    <div class="mb-7">
        <a
            href="{{ route('course.exams', $course) }}"
            wire:navigate
            class="inline-flex items-center gap-1.5 text-sm font-medium text-primary hover:text-primary/80 mb-4 transition-colors"
        >
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Volver a exámenes
        </a>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">
            {{ $exam->title }}
        </h1>
        <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
            Nota mínima para aprobar: <span class="font-semibold text-gray-900 dark:text-white">{{ $exam->passing_score_percent }}%</span>
        </p>
    </div>

    {{-- ── Formulario de preguntas ──────────────────────────────── --}}
    @if($step === 'take')
        <form wire:submit="submit" class="space-y-6">
            @foreach($questions as $index => $question)
                <fieldset class="rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 p-5 sm:p-6 shadow-sm">
                    <legend class="sr-only">Pregunta {{ $index + 1 }}</legend>

                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-2">
                        Pregunta {{ $index + 1 }} de {{ $questions->count() }}
                    </p>
                    <p class="text-base font-medium text-gray-900 dark:text-white mb-5 whitespace-pre-wrap leading-relaxed">
                        {{ $question->question_text }}
                    </p>

                    <div class="space-y-2.5" role="radiogroup" aria-label="Opciones de respuesta">
                        @foreach($question->options as $opt)
                            <label class="flex items-start gap-3 cursor-pointer rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 p-3.5 transition-colors hover:border-primary hover:bg-green-50 dark:hover:bg-gray-600 has-[:checked]:border-primary has-[:checked]:bg-green-50 dark:has-[:checked]:bg-gray-600 dark:has-[:checked]:border-primary">
                                <input
                                    type="radio"
                                    name="q_{{ $question->id }}"
                                    value="{{ $opt->id }}"
                                    wire:model.live="selectedOptions.{{ $question->id }}"
                                    class="mt-0.5 shrink-0 text-primary focus:ring-primary"
                                />
                                <span class="text-sm text-gray-800 dark:text-gray-100 leading-relaxed">{{ $opt->option_text }}</span>
                            </label>
                        @endforeach
                    </div>

                    @error('selectedOptions.' . $question->id)
                        <p class="mt-2.5 text-sm text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
                    @enderror
                </fieldset>
            @endforeach

            <div class="flex justify-end pt-2">
                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary hover:bg-primary/90 text-white font-semibold px-7 py-3 text-sm transition-colors shadow-sm hover:shadow-md disabled:opacity-50"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove wire:target="submit">Enviar respuestas</span>
                    <span wire:loading wire:target="submit" class="flex items-center gap-2">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                        </svg>
                        Corrigiendo…
                    </span>
                </button>
            </div>
        </form>
    @endif

    {{-- ── Resultados ───────────────────────────────────────────── --}}
    @if($step === 'results')
        <div class="space-y-5">
            {{-- Banner de resultado --}}
            @if($passed)
                <div class="rounded-xl border border-green-300 dark:border-green-700 bg-green-50 dark:bg-green-900 p-5 sm:p-6">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 shrink-0 text-green-600 dark:text-green-300 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <h2 class="text-lg font-bold text-green-800 dark:text-green-100">¡Aprobaste!</h2>
                            <p class="text-sm text-green-700 dark:text-green-200 mt-1">
                                Puntuación obtenida: <span class="font-bold text-green-900 dark:text-white">{{ number_format($scorePercent, 1) }}%</span>
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <div class="rounded-xl border border-red-300 dark:border-red-700 bg-red-50 dark:bg-red-900 p-5 sm:p-6">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 shrink-0 text-red-600 dark:text-red-300 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                        </svg>
                        <div>
                            <h2 class="text-lg font-bold text-red-800 dark:text-red-100">No alcanzaste la nota mínima</h2>
                            <p class="text-sm text-red-700 dark:text-red-200 mt-1">
                                Obtuviste <span class="font-bold text-red-900 dark:text-white">{{ number_format($scorePercent, 1) }}%</span>
                                (se requiere {{ $exam->passing_score_percent }}%).
                            </p>
                            <p class="text-sm text-red-700 dark:text-red-200 mt-1.5">
                                Podrás intentarlo de nuevo tras esperar <strong>{{ $exam->cooldown_minutes }} minutos</strong>. Revisa los temas indicados abajo.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Preguntas falladas --}}
                @if(count($wrongReview) > 0)
                    <div class="rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 p-5 sm:p-6 shadow-sm">
                        <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                            </svg>
                            Temas para repasar
                        </h3>
                        <ul class="space-y-3" role="list">
                            @foreach($wrongReview as $item)
                                <li class="rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 p-4">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white whitespace-pre-wrap mb-2">{{ $item['question'] }}</p>
                                    <ul class="space-y-1">
                                        @if(!empty($item['module']))
                                            <li class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                                                <span class="w-16 text-xs font-semibold uppercase text-gray-400 dark:text-gray-500 shrink-0">Módulo</span>
                                                <span>{{ $item['module'] }}</span>
                                            </li>
                                        @endif
                                        @if(!empty($item['lesson']))
                                            <li class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                                                <span class="w-16 text-xs font-semibold uppercase text-gray-400 dark:text-gray-500 shrink-0">Lección</span>
                                                <span>{{ $item['lesson'] }}</span>
                                            </li>
                                        @endif
                                        @if(empty($item['module']) && empty($item['lesson']))
                                            <li class="text-sm text-gray-500 dark:text-gray-400 italic">Repasa el contenido general del curso.</li>
                                        @endif
                                    </ul>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            @endif

            {{-- Volver a la lista --}}
            <div>
                <a
                    href="{{ route('course.exams', $course) }}"
                    wire:navigate
                    class="inline-flex items-center gap-2 rounded-lg border-2 border-primary text-primary dark:text-primary font-semibold px-5 py-2.5 text-sm hover:bg-primary/10 dark:hover:bg-primary/20 transition-colors"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Volver a la lista de exámenes
                </a>
            </div>
        </div>
    @endif
</div>
