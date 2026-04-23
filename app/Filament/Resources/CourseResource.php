<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\CourseStatus;
use App\Filament\Resources\CourseResource\Pages\CreateCourse;
use App\Filament\Resources\CourseResource\Pages\EditCourse;
use App\Filament\Resources\CourseResource\Pages\ListCourses;
use App\Filament\Resources\CourseResource\RelationManagers\ModulesRelationManager;
use App\Models\Category;
use App\Models\Course;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use UnitEnum;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static string|UnitEnum|null $navigationGroup = 'Academia';

    protected static ?int $navigationSort = 1;

    public static function getModelLabel(): string
    {
        return 'curso';
    }

    public static function getPluralModelLabel(): string
    {
        return 'cursos';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make()
                    ->columns(2)
                    ->schema([
                        // Columna Izquierda: Detalles Principales
                        Section::make('Detalles Principales')
                            ->schema([
                                Hidden::make('teacher_id')
                                    ->dehydrated(true),

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

                                Select::make('categories')
                                    ->label('Categorías')
                                    ->multiple()
                                    ->relationship('categories', 'name')
                                    ->preload()
                                    ->searchable()
                                    ->placeholder('Selecciona una o varias categorías'),

                                RichEditor::make('description')
                                    ->label('Descripción')
                                    ->fileAttachmentsDirectory('courses/descriptions')
                                    ->fileAttachmentsAcceptedFileTypes([
                                        'image/png',
                                        'image/jpeg',
                                        'image/gif',
                                        'image/webp',
                                        'application/pdf',
                                        'application/msword', // .doc
                                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // .docx
                                    ])
                                    ->fileAttachmentsMaxSize(20480) // 20 MB
                                    ->columnSpanFull(),
                            ]),

                        // Columna Derecha: Meta & Precio
                        Section::make('Meta & Precio')
                            ->schema([
                                ToggleButtons::make('cover_type')
                                    ->label('Portada')
                                    ->options([
                                        'image' => 'Imagen (adjuntar archivo)',
                                        'video' => 'Video (URL incrustada)',
                                    ])
                                    ->default('image')
                                    ->inline()
                                    ->required()
                                    ->live(),

                                Textarea::make('cover_video_embed')
                                    ->label('Código de inserción (iframe) o URL del video')
                                    ->placeholder('Pega la URL (ej. https://www.youtube.com/embed/xxx) o el HTML del iframe de YouTube, Vimeo, Bunny, etc.')
                                    ->helperText('No se sube archivo. Pega la URL o el código que te da la plataforma (YouTube, Vimeo, Bunny.net, etc.), igual que en lecciones.')
                                    ->rows(5)
                                    ->visible(fn ($get): bool => $get('cover_type') === 'video')
                                    ->columnSpanFull(),

                                FileUpload::make('image_url')
                                    ->label('Imagen de portada')
                                    ->helperText('Solo cuando la portada es "Imagen": sube una imagen. Si es "Video", esta imagen opcional se usa como miniatura.')
                                    ->disk('public')
                                    ->directory('courses')
                                    ->visibility('public')
                                    ->image()
                                    ->imageEditor()
                                    ->imageEditorAspectRatios([
                                        '16:9',
                                        '4:3',
                                        '1:1',
                                    ])
                                    ->imagePreviewHeight('250')
                                    ->maxSize(5120)
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                    ->downloadable()
                                    ->openable()
                                    ->visible(fn ($get): bool => $get('cover_type') === 'image')
                                    ->columnSpanFull(),

                                TextInput::make('price')
                                    ->label('Precio')
                                    ->numeric()
                                    ->prefix('S/ ')
                                    ->default(0)
                                    ->required(),

                                TextInput::make('sale_price')
                                    ->label('Precio rebajado')
                                    ->numeric()
                                    ->prefix('S/ ')
                                    ->placeholder('Opcional. Si se define, este es el precio que se cobra.')
                                    ->helperText('Dejar vacío para usar solo el precio normal.'),

                                ToggleButtons::make('status')
                                    ->label('Estado')
                                    ->options(CourseStatus::class)
                                    ->inline()
                                    ->required()
                                    ->default(CourseStatus::Draft),

                                TextInput::make('access_text')
                                    ->label('Acceso del curso')
                                    ->required()
                                    ->default('Acceso por 1 año')
                                    ->maxLength(255)
                                    ->columnSpanFull(),

                                TextInput::make('badge_label')
                                    ->label('Etiqueta del curso')
                                    ->required()
                                    ->default('Más demandado')
                                    ->maxLength(100)
                                    ->helperText('Texto corto que aparece en la esquina superior izquierda de la tarjeta del curso.')
                                    ->columnSpanFull(),

                                TextInput::make('profesor')
                                    ->label('Profesor (opcional)')
                                    ->helperText('Si deseas mostrar un nombre distinto al instructor logueado, escríbelo aquí. Si no, déjalo vacío.')
                                    ->maxLength(255)
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with(['teacher', 'categories']))
            ->columns([
                ImageColumn::make('image_url')
                    ->label('Imagen')
                    ->circular()
                    ->defaultImageUrl(url('/images/placeholder.png'))
                    ->getStateUsing(function ($record) {
                        if (!$record->image_url) {
                            return null;
                        }
                        return Storage::disk('public')->url($record->image_url);
                    }),

                TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('teacher.name')
                    ->label('Instructor')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('categories.name')
                    ->label('Categorías')
                    ->badge()
                    ->separator(', ')
                    ->wrap(),

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
                    ->money('PEN')
                    ->sortable()
                    ->formatStateUsing(function ($record) {
                        if ($record->hasSalePrice()) {
                            return 'S/ ' . number_format((float) $record->price, 2) . ' → S/ ' . number_format((float) $record->sale_price, 2);
                        }
                        return 'S/ ' . number_format((float) $record->price, 2);
                    }),

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
            ModulesRelationManager::class,
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

