<?php

declare(strict_types=1);

namespace App\Filament\Resources\ExamResource\RelationManagers;

use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\Lesson;
use App\Models\Module;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ExamQuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';

    protected static ?string $title = 'Preguntas';

    protected static ?string $recordTitleAttribute = 'question_text';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('question_text')
                    ->label('Pregunta')
                    ->required()
                    ->rows(4)
                    ->columnSpanFull(),

                Select::make('module_id')
                    ->label('Módulo para repaso (opcional)')
                    ->options(function (): array {
                        /** @var Exam $exam */
                        $exam = $this->getOwnerRecord();

                        return Module::query()
                            ->where('course_id', $exam->course_id)
                            ->orderBy('sort_order')
                            ->pluck('title', 'id')
                            ->all();
                    })
                    ->searchable()
                    ->nullable(),

                Select::make('lesson_id')
                    ->label('Lección para repaso (opcional)')
                    ->options(function (): array {
                        /** @var Exam $exam */
                        $exam = $this->getOwnerRecord();

                        return Lesson::query()
                            ->whereHas('module', fn ($q) => $q->where('course_id', $exam->course_id))
                            ->with('module')
                            ->orderBy('module_id')
                            ->orderBy('sort_order')
                            ->get()
                            ->mapWithKeys(fn (Lesson $lesson): array => [
                                $lesson->id => ($lesson->module->title ?? 'Módulo').' — '.$lesson->title,
                            ])
                            ->all();
                    })
                    ->searchable()
                    ->nullable()
                    ->helperText('Si el alumno falla, verá qué tema repasar según estos enlaces.'),

                TextInput::make('sort_order')
                    ->label('Orden')
                    ->numeric()
                    ->default(0)
                    ->required(),

                Repeater::make('options')
                    ->relationship()
                    ->label('Opciones (marca exactamente una como correcta)')
                    ->schema([
                        TextInput::make('option_text')
                            ->label('Texto de la opción')
                            ->required()
                            ->maxLength(500)
                            ->columnSpanFull(),
                        Toggle::make('is_correct')
                            ->label('Respuesta correcta')
                            ->inline(false),
                        TextInput::make('sort_order')
                            ->label('Orden')
                            ->numeric()
                            ->default(0)
                            ->hidden(),
                    ])
                    ->defaultItems(2)
                    ->minItems(2)
                    ->reorderableWithDragAndDrop()
                    ->collapsible()
                    ->itemLabel(fn (?array $state): ?string => isset($state['option_text']) ? Str::limit((string) $state['option_text'], 48) : 'Opción')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->withCount('options'))
            ->columns([
                TextColumn::make('question_text')
                    ->label('Pregunta')
                    ->limit(60)
                    ->searchable(),

                TextColumn::make('module.title')
                    ->label('Módulo')
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('lesson.title')
                    ->label('Lección')
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('options_count')
                    ->label('Opciones')
                    ->counts('options')
                    ->badge(),

                TextColumn::make('sort_order')
                    ->label('Orden')
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->after(function (ExamQuestion $record): void {
                        $this->enforceSingleCorrectOptionOrRollback($record);
                    }),
            ])
            ->actions([
                EditAction::make()
                    ->after(function (ExamQuestion $record): void {
                        $this->notifyIfInvalidOptions($record);
                    }),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order', 'asc');
    }

    /**
     * Tras crear: si no hay exactamente una correcta, elimina la pregunta y avisa.
     */
    private function enforceSingleCorrectOptionOrRollback(ExamQuestion $record): void
    {
        $record->load('options');
        $correct = $record->options->where('is_correct', true)->count();
        if ($correct === 1) {
            return;
        }

        $record->options()->delete();
        $record->delete();

        Notification::make()
            ->title('Opciones inválidas')
            ->body('Debe existir exactamente una opción marcada como correcta. La pregunta no se guardó.')
            ->danger()
            ->send();
    }

    /**
     * Tras editar: avisa si la configuración de correctas no es válida (los datos ya quedaron guardados).
     */
    private function notifyIfInvalidOptions(ExamQuestion $record): void
    {
        $record->load('options');
        $correct = $record->options->where('is_correct', true)->count();
        if ($correct === 1) {
            return;
        }

        Notification::make()
            ->title('Revisa las opciones')
            ->body('Debe existir exactamente una opción marcada como correcta.')
            ->warning()
            ->persistent()
            ->send();
    }
}
