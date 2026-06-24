<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    
    protected static ?string $navigationGroup = 'Sistem & Konfigurasi';

    protected static ?string $navigationLabel = 'Manajemen User';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->label('No. Telepon')
                    ->tel()
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\DateTimePicker::make('email_verified_at')
                    ->label('Email Diverifikasi Pada'),
                Forms\Components\TextInput::make('password')
                    ->label('Kata Sandi')
                    ->password()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('role')
                    ->label('Peran')
                    ->required(),
                Forms\Components\Textarea::make('address')
                    ->label('Alamat')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('city')
                    ->label('Kota')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Textarea::make('bio')
                    ->label('Bio')
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('profile_image')
                    ->label('Foto Profil')
                    ->image(),
                Forms\Components\DatePicker::make('wedding_date')
                    ->label('Tanggal Pernikahan'),
                Forms\Components\Toggle::make('prefer_whatsapp')
                    ->label('Prioritas WhatsApp')
                    ->required(),
                Forms\Components\Toggle::make('prefer_sms')
                    ->label('Prioritas SMS')
                    ->required(),
                Forms\Components\Toggle::make('prefer_email')
                    ->label('Prioritas Email')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('No. Telepon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->label('Verifikasi Email')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('role')
                    ->label('Peran'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Terdaftar')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Tanggal Diperbarui')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('city')
                    ->label('Kota')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('profile_image')
                    ->label('Foto Profil'),
                Tables\Columns\TextColumn::make('wedding_date')
                    ->label('Tanggal Pernikahan')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label('Tanggal Dihapus')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('prefer_whatsapp')
                    ->label('WhatsApp')
                    ->boolean(),
                Tables\Columns\IconColumn::make('prefer_sms')
                    ->label('SMS')
                    ->boolean(),
                Tables\Columns\IconColumn::make('prefer_email')
                    ->label('Email')
                    ->boolean(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
