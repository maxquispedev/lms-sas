<div class="max-w-4xl mx-auto py-8 sm:py-12 px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <a
            href="{{ route('student.dashboard') }}"
            class="inline-flex items-center gap-1.5 text-sm font-medium text-primary hover:text-primary/80 dark:text-primary dark:hover:text-primary/80 mb-5 transition-colors"
        >
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Volver a Mis cursos
        </a>
        <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white mb-2">
            Exámenes: {{ $course->title }}
        </h1>
        <p class="text-sm text-gray-600 dark:text-gray-300">
            Aprueba con al menos el porcentaje indicado. Si no alcanzas la nota, deberás esperar antes de reintentar.
        </p>
    </div>

    @if(session('error'))
        <div class="mb-6 rounded-lg border border-red-200 dark:border-red-700 bg-red-50 dark:bg-red-900/30 px-4 py-3 text-sm text-red-800 dark:text-red-200">
            {{ session('error') }}
        </div>
    @endif

    @if($examRows->isEmpty())
        <div class="rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 p-8 text-center text-gray-600 dark:text-gray-300">
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
                <li class="rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 p-5 sm:p-6 shadow-sm">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
                        {{-- Info del examen --}}
                        <div class="min-w-0">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $exam->title }}
                            </h2>
                            @if($exam->description)
                                <p class="mt-1.5 text-sm text-gray-600 dark:text-gray-300">{{ $exam->description }}</p>
                            @endif
                            <div class="mt-2.5 flex flex-wrap items-center gap-x-3 gap-y-1">
                                <span class="inline-flex items-center gap-1 text-xs font-medium text-gray-500 dark:text-gray-400">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z"/>
                                    </svg>
                                    {{ $exam->questions_count }} {{ $exam->questions_count === 1 ? 'pregunta' : 'preguntas' }}
                                </span>
                                <span class="text-gray-300 dark:text-gray-600">·</span>
                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Aprobar ≥ {{ $exam->passing_score_percent }}%</span>
                                <span class="text-gray-300 dark:text-gray-600">·</span>
                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Espera {{ $exam->cooldown_minutes }} min si repruebas</span>
                            </div>
                        </div>

                        {{-- Estado / Acción --}}
                        <div class="flex flex-col items-stretch sm:items-end gap-2 shrink-0">
                            @if($passed)
                                {{-- Aprobado --}}
                                <span class="inline-flex items-center justify-center gap-2 rounded-lg bg-green-100 dark:bg-green-900/40 border border-green-200 dark:border-green-700 px-4 py-2.5 text-sm font-semibold text-green-800 dark:text-green-200">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Aprobado
                                </span>

                            @elseif($cooldownEnds)
                                {{-- Cooldown activo: temporizador --}}
                                <div
                                    class="min-w-[200px] rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-primary/60 px-4 py-3.5"
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
                                            const pad = n => String(n).padStart(2, '0');
                                            this.label = (h > 0 ? h + 'h ' : '') + pad(m) + 'm ' + pad(sec) + 's';
                                        }
                                    }"
                                    x-init="update(); setInterval(() => update(), 1000)"
                                >
                                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1.5">
                                        Podrás reintentar en
                                    </p>
                                    <p
                                        x-show="remaining > 0"
                                        x-text="label"
                                        class="text-2xl font-mono font-bold text-gray-900 dark:text-white tracking-wide leading-none"
                                    ></p>
                                    <a
                                        href="{{ route('course.exam.take', [$course, $exam]) }}"
                                        wire:navigate
                                        x-show="remaining <= 0"
                                        x-cloak
                                        class="mt-1 inline-flex items-center justify-center w-full rounded-lg bg-primary hover:bg-primary/90 text-white font-semibold px-4 py-2.5 text-sm transition-colors"
                                    >
                                        Tomar examen
                                    </a>
                                </div>

                            @else
                                {{-- Disponible --}}
                                <a
                                    href="{{ route('course.exam.take', [$course, $exam]) }}"
                                    wire:navigate
                                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary hover:bg-primary/90 text-white font-semibold px-5 py-2.5 text-sm transition-colors shadow-sm hover:shadow-md"
                                >
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
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
