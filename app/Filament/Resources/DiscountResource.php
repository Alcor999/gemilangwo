<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DiscountResource\Pages;
use App\Filament\Resources\DiscountResource\RelationManagers;
use App\Models\Discount;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DiscountResource extends Resource
{
    protected static ?string $model = Discount::class;

    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';
    
    protected static ?string $navigationGroup = 'Pemasaran';

    protected static ?string $navigationLabel = 'Promosi & Diskon';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Promo')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('type')
                    ->label('Tipe Potongan')
                    ->required(),
                Forms\Components\TextInput::make('value')
                    ->label('Nilai')
                    ->required()
                    ->numeric(),
                Forms\Components\DateTimePicker::make('start_date')
                    ->label('Tanggal Mulai')
                    ->required(),
                Forms\Components\DateTimePicker::make('end_date')
                    ->label('Tanggal Selesai'),
                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif')
                    ->required(),
                Forms\Components\TextInput::make('usage_limit')
                    ->label('Batas Penggunaan')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('usage_count')
                    ->label('Jumlah Penggunaan')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('created_by')
                    ->label('Dibuat Oleh')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Promo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe Potongan'),
                Tables\Columns\TextColumn::make('value')
                    ->label('Nilai')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Tanggal Mulai')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('Tanggal Selesai')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                Tables\Columns\TextColumn::make('usage_limit')
                    ->label('Batas Penggunaan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('usage_count')
                    ->label('Jumlah Penggunaan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_by')
                    ->label('Dibuat Oleh')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Tanggal Diperbarui')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListDiscounts::route('/'),
            'create' => Pages\CreateDiscount::route('/create'),
            'view' => Pages\ViewDiscount::route('/{record}'),
            'edit' => Pages\EditDiscount::route('/{record}/edit'),
        ];
    }
}
