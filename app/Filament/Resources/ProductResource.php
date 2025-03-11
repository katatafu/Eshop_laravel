<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TextArea;
use Filament\Forms\Components\NumberInput;
use Filament\Forms\Components\Toggle;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Formulář pro název produktu
                Forms\Components\TextInput::make('name')
                    ->label('Product Name')
                    ->required()
                    ->maxLength(255),

                // Formulář pro popis produktu
                Forms\Components\TextArea::make('description')
                    ->label('Description')
                    ->required()
                    ->maxLength(1000),

                // Formulář pro cenu produktu
                Forms\Components\TextInput::make('price')
                ->label('Price')
                ->required()
                ->numeric()
                ->step(0.01)
                ->helperText('Enter the product price'),

                // Formulář pro SKU (Stock Keeping Unit)
                /*Forms\Components\TextInput::make('sku')
                    ->label('SKU')
                    ->required()
                    ->maxLength(255),*/

                // Formulář pro dostupnost na skladě
                Forms\Components\TextInput::make('in_stock')
                    ->label('In Stock')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Product Name'),
                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->money('usd'), // Můžeš použít měnu, např. 'usd'
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU'),
                Tables\Columns\BooleanColumn::make('in_stock')
                    ->label('In Stock')
                    ->trueColor('success') // Označí zeleně, pokud je skladem
                    ->falseColor('danger'), // Označí červeně, pokud není skladem
            ])
            ->filters([
                // Můžeš přidat filtry podle potřeby, např. filtry podle dostupnosti
                Tables\Filters\SelectFilter::make('in_stock')
                    ->options([
                        '1' => 'In Stock',
                        '0' => 'Out of Stock',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(), // Akce pro úpravu produktu
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(), // Akce pro hromadné smazání
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Pokud máš vztahy, přidej je sem
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
