<?php

declare(strict_types=1);

namespace App\Filament\Resources\CourseResource\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ModulesRelationManager extends RelationManager
{
    protected static string $relationship = 'modules';

    protected static ?string $recordTitleAttribute = 'title';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Nombre del Módulo')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set, $get) {
                        if ($get('slug') === '' || $get('slug') === null) {
                            $set('slug', Str::slug((string) $state));
                        }
                    })
                    ->maxLength(255),

                TextInput::make('slug')
                    ->label('Slug')
                    ->helperText('Se usa para la URL cuando el curso no tiene lecciones.')
                    ->required()
                    ->maxLength(255),

                Textarea::make('iframe_code')
                    ->label('Video del módulo (código iframe o URL)')
                    ->helperText('Pega la URL de YouTube/Vimeo/Bunny o el código iframe completo.')
                    ->rows(3)
                    ->columnSpanFull(),

                RichEditor::make('content')
                    ->label('Contenido del módulo')
                    ->helperText('Texto, recursos o descripción que se mostrará al ver el módulo.')
                    ->fileAttachmentsDirectory('modules/content')
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

                TextInput::make('sort_order')
                    ->label('Orden')
                    ->numeric()
                    ->default(0)
                    ->required(),

                Repeater::make('lessons')
                    ->relationship()
                    ->schema([
                        TextInput::make('title')
                            ->label('Título de la Lección')
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
                            ->maxLength(255),

                        Textarea::make('iframe_code')
                            ->label('Embed Code')
                            ->rows(2)
                            ->columnSpanFull(),

                        RichEditor::make('content')
                            ->label('Contenido y Recursos')
                            ->fileAttachmentsDirectory('lessons/content')
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

                        Toggle::make('is_free')
                            ->label('Vista Previa Gratuita')
                            ->default(false),

                        TextInput::make('sort_order')
                            ->label('Orden')
                            ->numeric()
                            ->default(0),
                    ])
                    ->reorderableWithButtons()
                    ->defaultItems(0)
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? 'Nueva Lección')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->withCount('lessons'))
            ->columns([
                TextColumn::make('title')
                    ->label('Módulo')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('lessons_count')
                    ->label('Lecciones')
                    ->counts('lessons')
                    ->sortable()
                    ->badge(),

                TextColumn::make('sort_order')
                    ->label('Orden')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order', 'asc');
    }
}

