<div class="max-w-4xl mx-auto py-8 sm:py-12 px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <a
            href="{{ route('student.dashboard') }}"
            class="text-sm font-medium text-primary hover:text-primary/80 dark:text-primary dark:hover:text-primary/90 mb-4 inline-block"
        >
            ← Volver a Mis cursos
        </a>
        <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-gray-100 mb-2">
            Exámenes: {{ $course->title }}
        </h1>
        <p class="text-sm text-gray-600 dark:text-gray-400">
            Aprueba con al menos el porcentaje indicado. Si no alcanzas la nota, deberás esperar el tiempo de espera antes de reintentar.
        </p>
    </div>

    @if(session('error'))
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-900/50 dark:bg-red-900/20 dark:text-red-200">
            {{ session('error') }}
        </div>
    @endif

    @if($examRows->isEmpty())
        <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-8 text-center text-gray-600 dark:text-gray-400">
            No hay exámenes publicados para este curso.
        </div>
    @else
        <ul class="space-y-4" role="list">
            @foreach($examRows as $row)
                @php
                    /** @var \App\Models\Exam $exam */
                    $exam = $row['exam'];
                    $passed = $row['passed'];
                    $cooldownEnds = $row['cooldown_ends_at'];
                @endphp
                <li class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5 sm:p-6 shadow-sm">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                {{ $exam->title }}
                            </h2>
                            @if($exam->description)
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $exam->description }}</p>
                            @endif
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-500">
                                {{ $exam->questions_count }} {{ $exam->questions_count === 1 ? 'pregunta' : 'preguntas' }}
                                · Aprobar: ≥ {{ $exam->passing_score_percent }}%
                                · Espera si repruebas: {{ $exam->cooldown_minutes }} min
                            </p>
                        </div>
                        <div class="flex flex-col items-stretch sm:items-end gap-2 shrink-0">
                            @if($passed)
                                <span class="inline-flex items-center justify-center rounded-lg bg-green-100 dark:bg-green-900/30 px-4 py-2 text-sm font-semibold text-green-800 dark:text-green-200">
                                    Aprobado
                                </span>
                            @elseif($cooldownEnds)
                                <div
                                    class="rounded-lg bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 px-4 py-3 text-sm text-amber-900 dark:text-amber-100"
                                    x-data="{
                                        endMs: new Date(@js($cooldownEnds)).getTime(),
                                        remaining: 0,
                                        label: '',
                                        update() {
                                            const s = Math.max(0, Math.floor((this.endMs - Date.now()) / 1000));
                                            this.remaining = s;
                                            const h = Math.floor(s / 3600);
                                            const m = Math.floor((s % 3600) / 60);
                                            const sec = s % 60;
                                            this.label = (h > 0 ? h + 'h ' : '') + m + 'm ' + sec + 's';
                                        }
                                    }"
                                    x-init="update(); setInterval(() => update(), 1000)"
                                >
                                    <p class="font-semibold mb-1">Podrás reintentar en:</p>
                                    <p class="text-lg font-mono font-bold" x-show="remaining > 0" x-text="label"></p>
                                    <a
                                        href="{{ route('course.exam.take', [$course, $exam]) }}"
                                        wire:navigate
                                        x-show="remaining <= 0"
                                        class="mt-3 inline-block text-center w-full rounded-lg bg-primary text-white font-medium px-4 py-2 hover:bg-primary/90"
                                    >
                                        Tomar examen
                                    </a>
                                </div>
                            @else
                                <a
                                    href="{{ route('course.exam.take', [$course, $exam]) }}"
                                    wire:navigate
                                    class="inline-flex justify-center rounded-lg bg-primary hover:bg-primary/90 text-white font-medium px-5 py-2.5 text-sm transition-colors"
                                >
                                    Tomar examen
                                </a>
                            @endif
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
</div>
