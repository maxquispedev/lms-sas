<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\CourseStatus;
use App\Filament\Resources\CourseResource\Pages\CreateCourse;
use App\Filament\Resources\CourseResource\Pages\EditCourse;
use App\Filament\Resources\CourseResource\Pages\ListCourses;
use App\Models\Course;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)
                    ->schema([
                        // Columna Izquierda: Detalles Principales
                        Section::make('Detalles Principales')
                            ->schema([
                                Select::make('teacher_id')
                                    ->label('Instructor')
                                    ->relationship('teacher', 'name')
                                    ->searchable()
                                    ->required()
                                    ->preload(),

                                TextInput::make('title')
                                    ->label('Título')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        if ($get('slug') === '' || $get('slug') === null) {
                                            $set('slug', Str::slug($state));
                                        }
                                    })
                                    ->maxLength(255),

                                TextInput::make('slug')
                                    ->label('Slug')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255),

                                RichEditor::make('description')
                                    ->label('Descripción')
                                    ->columnSpanFull(),
                            ]),

                        // Columna Derecha: Meta & Precio
                        Section::make('Meta & Precio')
                            ->schema([
                                FileUpload::make('image_url')
                                    ->label('Imagen')
                                    ->disk('public')
                                    ->directory('courses')
                                    ->image()
                                    ->imageEditor()
                                    ->imageEditorAspectRatios([
                                        '16:9',
                                        '4:3',
                                        '1:1',
                                    ]),

                                TextInput::make('price')
                                    ->label('Precio')
                                    ->numeric()
                                    ->prefix('$')
                                    ->default(0)
                                    ->required(),

                                ToggleButtons::make('status')
                                    ->label('Estado')
                                    ->options(CourseStatus::class)
                                    ->inline()
                                    ->required()
                                    ->default(CourseStatus::Draft),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with('teacher'))
            ->columns([
                ImageColumn::make('image_url')
                    ->label('Imagen')
                    ->circular()
                    ->defaultImageUrl(url('/images/placeholder.png')),

                TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('teacher.name')
                    ->label('Instructor')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (CourseStatus $state): string => $state->getColor())
                    ->formatStateUsing(fn (CourseStatus $state): string => match ($state) {
                        CourseStatus::Draft => 'Borrador',
                        CourseStatus::Published => 'Publicado',
                        CourseStatus::Archived => 'Archivado',
                    }),

                TextColumn::make('price')
                    ->label('Precio')
                    ->money('USD')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options(CourseStatus::class),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCourses::route('/'),
            'create' => CreateCourse::route('/create'),
            'edit' => EditCourse::route('/{record}/edit'),
        ];
    }
}

