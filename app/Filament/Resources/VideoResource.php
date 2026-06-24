<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VideoResource\Pages;
use App\Filament\Resources\VideoResource\RelationManagers;
use App\Models\Video;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VideoResource extends Resource
{
    protected static ?string $model = Video::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';
    
    protected static ?string $navigationGroup = 'Pemasaran';

    protected static ?string $navigationLabel = 'Galeri Video';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('package_id')
                    ->label('ID Paket')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('title')
                    ->label('Judul Video')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('type')
                    ->label('Tipe Video')
                    ->required(),
                Forms\Components\TextInput::make('video_path')
                    ->label('Path Video')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('youtube_url')
                    ->label('URL YouTube')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('thumbnail_path')
                    ->label('Path Thumbnail')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif')
                    ->required(),
                Forms\Components\TextInput::make('order')
                    ->label('Urutan')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('package_id')
                    ->label('ID Paket')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Video')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe Video'),
                Tables\Columns\TextColumn::make('video_path')
                    ->label('Path Video')
                    ->searchable(),
                Tables\Columns\TextColumn::make('youtube_url')
                    ->label('URL YouTube')
                    ->searchable(),
                Tables\Columns\TextColumn::make('thumbnail_path')
                    ->label('Path Thumbnail')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                Tables\Columns\TextColumn::make('order')
                    ->label('Urutan')
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
            'index' => Pages\ListVideos::route('/'),
            'create' => Pages\CreateVideo::route('/create'),
            'view' => Pages\ViewVideo::route('/{record}'),
            'edit' => Pages\EditVideo::route('/{record}/edit'),
        ];
    }
}
