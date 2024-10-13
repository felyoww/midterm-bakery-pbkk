<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\OrderResource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Order;

class LatestOrders extends BaseWidget
{
    protected int | string | array $columnsSpan = 'full';

    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(OrderResource::getEloquentQuery())
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at', 'desc')
            ->columns([
                
                TextColumn::make('id')
                    ->label('Order ID')
                    ->searchable(),

                TextColumn::make('user.name')
                ->searchable(),
                
                TextColumn::make('grand_total')
                    ->money('IDR'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state):string => match($state) {
                        'new' => 'info',
                        'processing' => 'warning',
                        'shipped' => 'success',
                        'delivered' => 'success',
                        'cancelled' => 'danger'

                    })
                    ->icon(fn(string $state):string => match($state) {
                        'new' => 'heroicon-m-sparkles',
                                'processing' => 'heroicon-m-arrow-path',
                                'shipped' => 'heroicon-m-truck',
                                'delivered' => 'heroicon-m-check-badge',
                                'canceled' => 'heroicon-m-x-circle'
                    })
                    ->sortable(),
                
                TextColumn::make('payment_method')
                    ->searchable()
                    ->sortable(),

                
                TextColumn::make('payment_status')
                    ->searchable()
                    ->badge()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Order Date')
                    ->dateTime(),
            ])
            ->actions([
                Action::make('View Order')
                    ->url(fn(Order $record): string => OrderResource::getUrl('view', ['record' => $record])) // Fix the syntax for the URL
                    ->color('info')
                    ->icon('heroicon-o-eye'), // Remove the comma from this line
                Tables\Actions\DeleteAction::make(), // Add a comma after this line
            ])

            ->filters([
                //
            ])
            ->headerActions([
               // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Action::make('View Order')
                    ->url(fn(Order $record): string => OrderResource::getUrl('view', ['record' => $record])) // Fix the syntax for the URL
                    ->color('info')
                    ->icon('heroicon-o-eye'), // Remove the comma from this line
                Tables\Actions\DeleteAction::make(), // Add a comma after this line
            ])
            
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
