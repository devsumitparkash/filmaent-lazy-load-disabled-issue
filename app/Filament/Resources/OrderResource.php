<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatus;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Business\Order\Order;
use App\Models\Business\Order\OrderItem;
use App\Models\Business\Order\OrderTotal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getEloquentQuery(): Builder
    {

        return parent::getEloquentQuery()
            ->with([
                'items.order'
            ]);
    }

    public static function getDetailsInfolistSchema(Infolist $infolist): array
    {
        return [
            Infolists\Components\TextEntry::make('customer.name')
                ->label('Customer Name'),
            Infolists\Components\TextEntry::make('customer.email')
                ->label('Customer Email'),
            Infolists\Components\TextEntry::make('status')
                ->label('Status')
                ->badge(),
            Infolists\Components\TextEntry::make('table_number')
                ->label('Table Number'),
            Infolists\Components\TextEntry::make('comment')
                ->label('Notes')
                ->columnSpanFull(),
            Infolists\Components\TextEntry::make('total')
                ->label('Total')
                ->formatStateUsing(fn (Order $record, $state): string => $record->formatted_total_with_currency),
        ];
    }

    public static function getItemsRepeaterForInfolistSchema(Infolist $infolist): RepeatableEntry
    {
        return RepeatableEntry::make('items')
            ->label('')
            ->schema([
                Infolists\Components\TextEntry::make('item_name')
                    ->label('')
                    ->columnSpan(6),
                Infolists\Components\TextEntry::make('quantity')
                    ->label('')
                    ->alignEnd()
                    ->columnSpan(2),
                Infolists\Components\TextEntry::make('price')
                    ->label('')
                    ->formatStateUsing(fn (OrderItem $record, string $state): string => $record->formatted_price_with_currency)
                    ->alignEnd()
                    ->columnSpan(2),
                Infolists\Components\TextEntry::make('total')
                    ->label('')
                    ->formatStateUsing(fn (OrderItem $record, string $state): string => $record->formatted_total_with_currency)
                    ->alignEnd()
                    ->columnSpan(2),
            ])
            ->columns(12)
            ->contained(false);
    }

    public static function getTotalsRepeaterForInfolistSchema(Infolist $infolist): RepeatableEntry
    {
        return RepeatableEntry::make('totals')
            ->label('')
            ->schema([
                Infolists\Components\TextEntry::make('title')
                    ->label('')
                    ->columnSpan(10),
                Infolists\Components\TextEntry::make('code')
                    ->label('')
                    ->formatStateUsing(fn (OrderTotal $record, string $state): string => $record->formatted_value_with_currency)
                    ->alignEnd()
                    ->columnSpan(2),
            ])
            ->columns(12)
            ->contained(false);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('customer.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge(),
                Tables\Columns\TextColumn::make('total')
                    // ->summarize([
                    //     Tables\Columns\Summarizers\Sum::make()
                    //         ->money(),
                    // ])
                    ->sortable()
                    ->state(function (Order $record): string {
                        return $record->formatted_total_with_currency;
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Order Date')
                    ->date()
                    ->dateTimeTooltip(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([])
            ->recordUrl(null);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Group::make()
                    ->schema([

                        Infolists\Components\Section::make()
                            ->schema([
                                Infolists\Components\Section::make()
                                    ->schema(
                                        [

                                        ... self::getDetailsInfolistSchema($infolist),


                                            // Infolists\Components\TextEntry::make('order_number')
                                            //         ->label('Order Number'),

                                            // \Filament\Infolists\Components\Actions::make([
                                            //     \Filament\Infolists\Components\Actions\Action::make('testing')
                                            //         ->icon('heroicon-m-star')
                                            //         ->requiresConfirmation()
                                            //         ->mountUsing(function (Order $record) {
                                            //             $record->load([
                                            //                 'items.order',
                                            //                 'totals.order',
                                            //             ]);

                                            //         })
                                            //         ->form([
                                            //             \Filament\Forms\Components\Select::make('order_status')
                                            //             ->options(
                                            //                 collect(OrderStatus::cases())->mapWithKeys(fn ($case) => [
                                            //                     $case->value => $case->getLabel(),
                                            //                 ])->toArray(),
                                            //             )
                                            //             ->default(fn (Order $record) => $record->status->value)
                                            //             ->required()
                                            //         ])
                                            //         ->action(function ( $record, array $data) {
                                            //             dump($record);
                                            //             dd("thsiiscalled");
                                            //         }),
                                            // ]),
                                        ]
                                    )
                                    ->columns(2),
                                Infolists\Components\Section::make('Order Items')
                                    ->schema([
                                        Infolists\Components\Grid::make()
                                            ->schema([

                                                Infolists\Components\TextEntry::make('')
                                                    ->default('Item Name')
                                                    ->label('')
                                                    ->weight('bold')
                                                    ->columnSpan(6),
                                                Infolists\Components\TextEntry::make('')
                                                    ->default('Qty')
                                                    ->label('')
                                                    ->weight('bold')
                                                    ->alignEnd()
                                                    ->columnSpan(2),
                                                Infolists\Components\TextEntry::make('')
                                                    ->default('Price')
                                                    ->label('')
                                                    ->weight('bold')
                                                    ->alignEnd()
                                                    ->columnSpan(2),
                                                Infolists\Components\TextEntry::make('')
                                                    ->default('Total')
                                                    ->label('')
                                                    ->weight('bold')
                                                    ->alignEnd()
                                                    ->columnSpan(2),

                                            ])
                                            ->columns(12),

                                        static::getItemsRepeaterForInfolistSchema($infolist),

                                    ]),

                                Infolists\Components\Section::make('Totals')
                                    ->schema([
                                       static::getTotalsRepeaterForInfolistSchema($infolist),

                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
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
            'index' => Pages\ListOrders::route('/'),
            //'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            // 'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
