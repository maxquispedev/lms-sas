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
                    ->maxLength(255),

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

