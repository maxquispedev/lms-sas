<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\ExamResource\Pages\CreateExam;
use App\Filament\Resources\ExamResource\Pages\EditExam;
use App\Filament\Resources\ExamResource\Pages\ListExams;
use App\Filament\Resources\ExamResource\RelationManagers\ExamQuestionsRelationManager;
use App\Models\Course;
use App\Models\Exam;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class ExamResource extends Resource
{
    protected static ?string $model = Exam::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static string|UnitEnum|null $navigationGroup = 'Academia';

    protected static ?int $navigationSort = 2;

    public static function getModelLabel(): string
    {
        return 'examen';
    }

    public static function getPluralModelLabel(): string
    {
        return 'exámenes';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Datos del examen')
                    ->schema([
                        Select::make('course_id')
                            ->label('Curso')
                            ->relationship('course', 'title')
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('title')
                            ->label('Título')
                            ->required()
                            ->maxLength(255),

                        Textarea::make('description')
                            ->label('Descripción')
                            ->rows(3)
                            ->columnSpanFull(),

                        TextInput::make('passing_score_percent')
                            ->label('Nota mínima para aprobar (%)')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(100)
                            ->default(70)
                            ->required()
                            ->suffix('%'),

                        TextInput::make('cooldown_minutes')
                            ->label('Espera tras no aprobar (minutos)')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(10080)
                            ->default(60)
                            ->required()
                            ->helperText('Tras reprobar, el alumno deberá esperar este tiempo antes de reintentar.'),

                        TextInput::make('sort_order')
                            ->label('Orden')
                            ->numeric()
                            ->default(0)
                            ->required(),

                        Toggle::make('is_published')
                            ->label('Publicado')
                            ->helperText('Solo los exámenes publicados son visibles para los alumnos.')
                            ->default(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with('course'))
            ->columns([
                TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('course.title')
                    ->label('Curso')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('passing_score_percent')
                    ->label('Aprobado ≥')
                    ->suffix('%')
                    ->sortable(),

                TextColumn::make('cooldown_minutes')
                    ->label('Cooldown (min)')
                    ->sortable(),

                IconColumn::make('is_published')
                    ->label('Publicado')
                    ->boolean(),

                TextColumn::make('sort_order')
                    ->label('Orden')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('course_id')
                    ->label('Curso')
                    ->options(fn (): array => Course::query()->orderBy('title')->pluck('title', 'id')->all()),
                SelectFilter::make('is_published')
                    ->label('Estado')
                    ->options([
                        '1' => 'Publicado',
                        '0' => 'Borrador',
                    ]),
            ])
            ->defaultSort('sort_order', 'asc');
    }

    public static function getRelations(): array
    {
        return [
            ExamQuestionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListExams::route('/'),
            'create' => CreateExam::route('/create'),
            'edit' => EditExam::route('/{record}/edit'),
        ];
    }
}
