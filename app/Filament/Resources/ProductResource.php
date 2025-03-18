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
use App\Models\ProductImage;

use Filament\Forms\Components\FileUpload;
class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Form for product name
                Forms\Components\TextInput::make('name')
                    ->label('Product Name')
                    ->required()
                    ->maxLength(255),

                // Form for product description
                Forms\Components\TextArea::make('description')
                    ->label('Description')
                    ->required()
                    ->maxLength(1000),

                // Form for product price
                Forms\Components\TextInput::make('price')
                    ->label('Price')
                    ->required()
                    ->numeric()
                    ->step(0.01)
                    ->helperText('Enter the product price'),

                // Form for SKU (Stock Keeping Unit)
                Forms\Components\TextInput::make('sku')
                    ->label('SKU')
                    ->required()
                    ->maxLength(255),

                // Form for stock availability
                Forms\Components\TextInput::make('in_stock')
                    ->label('In Stock')
                    ->required(),

                // Form for uploading images (multiple uploads)
                Forms\Components\FileUpload::make('product_images')
                    ->label('Product Images')
                    ->image()
                    ->directory('gallery') // Directory to store files
                    ->maxSize(5120) // Max file size (5MB)
                    ->multiple() // Allow uploading multiple images
                    ->helperText('Upload one or more images for the product gallery')
                    ->disk('public')
                    ->columnSpan('full')
                    ->required() // At least one image is required
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        if ($state) {
                            // Get the current product model (the record being edited/created)
                            $product = $get('record'); 

                            // Ensure product is valid
                            if ($product) {
                                // Iterate over each uploaded file
                                foreach ($state as $file) {
                                    // Store the file and get its path
                                    $filePath = $file->store('gallery', 'public');

                                    // Save the image to the product_images table
                                    $product->images()->create([
                                        'file_path' => $filePath,
                                    ]);
                                }
                            } else {
                                logger('Product record not found.');
                            }
                        }
                    }),
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
                    ->money('usd'),
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU'),
                Tables\Columns\BooleanColumn::make('in_stock')
                    ->label('In Stock')
                    ->trueColor('success')
                    ->falseColor('danger'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('in_stock')
                    ->options([
                        '1' => 'In Stock',
                        '0' => 'Out of Stock',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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