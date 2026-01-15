<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\CourseStatus;
use App\Enums\OrderStatus;
use App\Enums\PaymentGateway;
use App\Filament\Resources\OrderResource\Pages\CreateOrder;
use App\Filament\Resources\OrderResource\Pages\EditOrder;
use App\Filament\Resources\OrderResource\Pages\ListOrders;
use App\Models\Course;
use App\Models\Order;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingCart;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Usuario')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required()
                    ->preload(),

                Select::make('course_id')
                    ->label('Curso a Vender')
                    ->relationship(
                        name: 'course',
                        titleAttribute: 'title',
                        modifyQueryUsing: fn ($query) => $query->where('status', CourseStatus::Published)
                    )
                    ->required()
                    ->searchable()
                    ->preload(),

                TextInput::make('transaction_id')
                    ->label('ID Referencia (Opcional)')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with('user'))
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('Usuario')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('PEN')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (OrderStatus $state): string => $state->getColor())
                    ->formatStateUsing(fn (OrderStatus $state): string => match ($state) {
                        OrderStatus::Pending => 'Pendiente',
                        OrderStatus::Paid => 'Pagado',
                        OrderStatus::Failed => 'Fallido',
                    }),

                TextColumn::make('payment_gateway')
                    ->label('Pasarela')
                    ->badge()
                    ->color(fn (PaymentGateway $state): string => $state->getColor())
                    ->formatStateUsing(fn (PaymentGateway $state): string => match ($state) {
                        PaymentGateway::Manual => 'Manual',
                        PaymentGateway::Culqi => 'Culqi',
                    }),

                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => ListOrders::route('/'),
            'create' => CreateOrder::route('/create'),
            'edit' => EditOrder::route('/{record}/edit'),
        ];
    }
}

