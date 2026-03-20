<div class="max-w-3xl mx-auto py-8 sm:py-12 px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a
            href="{{ route('course.exams', $course) }}"
            class="text-sm font-medium text-primary hover:text-primary/80 dark:text-primary mb-2 inline-block"
            wire:navigate
        >
            ← Volver a exámenes
        </a>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-gray-100">
            {{ $exam->title }}
        </h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
            Nota mínima para aprobar: {{ $exam->passing_score_percent }}%
        </p>
    </div>

    @if($step === 'take')
        <form wire:submit="submit" class="space-y-8">
            @foreach($questions as $index => $question)
                <fieldset class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5 shadow-sm">
                    <legend class="sr-only">Pregunta {{ $index + 1 }}</legend>
                    <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-2">
                        Pregunta {{ $index + 1 }} de {{ $questions->count() }}
                    </p>
                    <p class="text-base text-gray-900 dark:text-gray-100 font-medium mb-4 whitespace-pre-wrap">
                        {{ $question->question_text }}
                    </p>
                    <div class="space-y-2" role="radiogroup" aria-label="Opciones de respuesta">
                        @foreach($question->options as $opt)
                            <label class="flex items-start gap-3 cursor-pointer rounded-lg border border-gray-100 dark:border-gray-600 p-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                                <input
                                    type="radio"
                                    name="q_{{ $question->id }}"
                                    value="{{ $opt->id }}"
                                    wire:model.live="selectedOptions.{{ $question->id }}"
                                    class="mt-1 text-primary focus:ring-primary"
                                />
                                <span class="text-sm text-gray-800 dark:text-gray-200">{{ $opt->option_text }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('selectedOptions.' . $question->id)
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </fieldset>
            @endforeach

            <div class="flex flex-col sm:flex-row gap-3 sm:justify-end">
                <button
                    type="submit"
                    class="inline-flex justify-center items-center rounded-lg bg-primary hover:bg-primary/90 text-white font-semibold px-6 py-3 text-sm transition-colors disabled:opacity-50"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove wire:target="submit">Enviar respuestas</span>
                    <span wire:loading wire:target="submit">Corrigiendo…</span>
                </button>
            </div>
        </form>
    @endif

    @if($step === 'results')
        <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 sm:p-8 shadow-sm space-y-6">
            @if($passed)
                <div class="rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 p-4">
                    <h2 class="text-lg font-bold text-green-800 dark:text-green-200">¡Aprobaste!</h2>
                    <p class="text-sm text-green-700 dark:text-green-300 mt-1">
                        Puntuación: <strong>{{ number_format($scorePercent, 2) }}%</strong>
                    </p>
                </div>
            @else
                <div class="rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 p-4">
                    <h2 class="text-lg font-bold text-red-800 dark:text-red-200">No alcanzaste la nota mínima</h2>
                    <p class="text-sm text-red-700 dark:text-red-300 mt-1">
                        Obtuviste <strong>{{ number_format($scorePercent, 2) }}%</strong> (se requiere {{ $exam->passing_score_percent }}%).
                    </p>
                    <p class="text-sm text-red-700 dark:text-red-300 mt-2">
                        Podrás intentar de nuevo pasada <strong>{{ $exam->cooldown_minutes }} minutos</strong> desde ahora. Revisa los temas indicados abajo.
                    </p>
                </div>

                @if(count($wrongReview) > 0)
                    <div>
                        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-3">
                            Preguntas falladas y temas para repasar
                        </h3>
                        <ul class="space-y-3" role="list">
                            @foreach($wrongReview as $item)
                                <li class="rounded-lg border border-gray-200 dark:border-gray-600 p-4 text-sm">
                                    <p class="font-medium text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $item['question'] }}</p>
                                    <ul class="mt-2 text-gray-600 dark:text-gray-400 list-disc list-inside space-y-1">
                                        @if(!empty($item['module']))
                                            <li><span class="font-medium text-gray-700 dark:text-gray-300">Módulo:</span> {{ $item['module'] }}</li>
                                        @endif
                                        @if(!empty($item['lesson']))
                                            <li><span class="font-medium text-gray-700 dark:text-gray-300">Lección:</span> {{ $item['lesson'] }}</li>
                                        @endif
                                        @if(empty($item['module']) && empty($item['lesson']))
                                            <li>Sin tema vinculado — repasa el contenido general del curso.</li>
                                        @endif
                                    </ul>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            @endif

            <a
                href="{{ route('course.exams', $course) }}"
                wire:navigate
                class="inline-flex justify-center rounded-lg border-2 border-primary text-primary font-semibold px-6 py-2.5 text-sm hover:bg-primary/10"
            >
                Volver a la lista de exámenes
            </a>
        </div>
    @endif
</div>
