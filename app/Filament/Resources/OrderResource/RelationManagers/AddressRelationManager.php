<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;

class AddressRelationManager extends RelationManager
{
    protected static string $relationship = 'address'; // Ensure this relationship matches the model

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('first_name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('last_name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('phone')
                    ->required()
                    ->tel()
                    ->maxLength(20),

                TextInput::make('city')
                    ->required()
                    ->maxLength(255),

                TextInput::make('state')
                    ->required()
                    ->maxLength(255),

                TextInput::make('zip_code')
                    ->required()
                    ->numeric()
                    ->maxLength(10),

                Textarea::make('street_address')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('street_address')
            ->columns([
                TextColumn::make('first_name')
                    ->label('First Name'),
                
                TextColumn::make('last_name')
                    ->label('Last Name'),

                TextColumn::make('phone')
                    ->label('Phone'),

                TextColumn::make('city')
                    ->label('City'),

                TextColumn::make('state')
                    ->label('State'),

                TextColumn::make('zip_code')
                    ->label('Zip Code'),

                TextColumn::make('street_address')
                    ->label('Street Address'),
            ])
            ->filters([/* Add filters if needed */])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
