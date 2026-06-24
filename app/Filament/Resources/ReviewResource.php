<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Filament\Resources\ReviewResource\RelationManagers;
use App\Models\Review;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';
    
    protected static ?string $navigationGroup = 'Pemasaran';

    protected static ?string $navigationLabel = 'Review Pelanggan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('order_id')
                    ->label('ID Pesanan')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('user_id')
                    ->label('ID Pelanggan')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('rating')
                    ->label('Rating')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('title')
                    ->label('Judul Ulasan')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Textarea::make('comment')
                    ->label('Komentar')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('content')
                    ->label('Konten')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('helpful_count')
                    ->label('Membantu')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('unhelpful_count')
                    ->label('Tidak Membantu')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\Toggle::make('is_verified')
                    ->label('Terverifikasi')
                    ->required(),
                Forms\Components\Toggle::make('is_approved')
                    ->label('Disetujui')
                    ->required(),
                Forms\Components\Toggle::make('is_featured')
                    ->label('Ditampilkan Utama')
                    ->required(),
                Forms\Components\TextInput::make('package_id')
                    ->label('ID Paket')
                    ->numeric()
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_id')
                    ->label('ID Pesanan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user_id')
                    ->label('ID Pelanggan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rating')
                    ->label('Rating')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Ulasan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('helpful_count')
                    ->label('Membantu')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('unhelpful_count')
                    ->label('Tidak Membantu')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_verified')
                    ->label('Terverifikasi')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_approved')
                    ->label('Disetujui')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Ditampilkan Utama')
                    ->boolean(),
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
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label('Tanggal Dihapus')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('package_id')
                    ->label('ID Paket')
                    ->numeric()
                    ->sortable(),
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
            'index' => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'view' => Pages\ViewReview::route('/{record}'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }
}
