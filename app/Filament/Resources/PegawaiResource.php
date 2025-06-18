<?php

namespace App\Filament\Resources;

use App\Filament\Imports\PegawaiImporter;
use App\Filament\Resources\PegawaiResource\Pages;
use App\Filament\Resources\PegawaiResource\RelationManagers;
use App\Models\Pegawai;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\ImportAction;
use Filament\Actions\Imports\Models\Import;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PegawaiResource extends Resource
{
    protected static ?string $model = Pegawai::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Kelola Pengguna';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('nip')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('golongan')
                    ->options([
                        'I A' => 'I A',
                        'I B' => 'I B',
                        'I C' => 'I C',
                        'I D' => 'I D',
                        'II A' => 'II A',
                        'II B' => 'II B',
                        'II C' => 'II C',
                        'II D' => 'II D',
                        'III A' => 'III A',
                        'III B' => 'III B',
                        'III C' => 'III C',
                        'III D' => 'III D',
                        'IV A' => 'IV A',
                        'IV B' => 'IV B',
                        'IV C' => 'IV C',
                        'IV D' => 'IV D',
                        'IV E' => 'IV E'
                    ])
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $pangkatMap = [
                            'I A' => 'Juru Muda',
                            'I B' => 'Juru Muda Tingkat I',
                            'I C' => 'Juru',
                            'I D' => 'Juru Tingkat I',
                            'II A' => 'Pengatur Muda',
                            'II B' => 'Pengatur Muda Tingkat I',
                            'II C' => 'Pengatur',
                            'II D' => 'Pengatur Tingkat I',
                            'III A' => 'Penata Muda',
                            'III B' => 'Penata Muda Tingkat I',
                            'III C' => 'Penata',
                            'III D' => 'Penata Tingkat I',
                            'IV A' => 'Pembina',
                            'IV B' => 'Pembina Tingkat I',
                            'IV C' => 'Pembina Utama Muda',
                            'IV D' => 'Pembina Utama Madya',
                            'IV E' => 'Pembina Utama'
                        ];
                        $set('pangkat', $pangkatMap[$state] ?? '');
                    }),
                Forms\Components\TextInput::make('pangkat')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('jabatan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('divisi')
                    ->maxLength(255),
                Forms\Components\TextInput::make('no_hp')
                    ->required()
                    ->maxLength(255),   
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nip')
                    ->sortable(),
                Tables\Columns\TextColumn::make('golongan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pangkat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jabatan')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('divisi')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
   Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                ImportAction::make()
                    ->importer(PegawaiImporter::class),
                CreateAction::make()
                    ->label('Tambah Data Pegawai')
                    ->icon('heroicon-o-plus'),
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
            'index' => Pages\ListPegawais::route('/'),
            'create' => Pages\CreatePegawai::route('/create'),
            'edit' => Pages\EditPegawai::route('/{record}/edit'),
        ];
    }
}
