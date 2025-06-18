<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuratEdaranResource\Pages;
use App\Filament\Resources\SuratEdaranResource\Pages\CreateSuratEdaran;
use App\Filament\Resources\SuratEdaranResource\Pages\EditSuratEdaran;
use App\Filament\Resources\SuratEdaranResource\RelationManagers;
use App\Models\SuratEdaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SuratEdaranResource extends Resource
{
    protected static ?string $model = SuratEdaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Buat Surat';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('surat_keluar_id')
                    // ->disabled(fn ($livewire) => $livewire instanceof CreateSuratEdaran)
                    // ->required(fn ($livewire) => $livewire instanceof EditSuratEdaran)
                    ->disabled()
                    ->hint('Nomor agenda akan muncul setelah surat disimpan')
                    ->label('Nomor Agenda Surat Keluar'),
                Forms\Components\Textarea::make('kepada')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('no_surat')
                    ->label('Nomor Surat')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('tahun_edaran')
                    ->label('Tahun')
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('isi_surat')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\DatePicker::make('tgl_surat')
                    ->label('Tanggal Surat')
                    ->required(),
                Forms\Components\TextInput::make('jabatan_ttd')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nama_ttd')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no_surat')
                    ->label('Nomor Surat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tahun_edaran')
                    // ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tgl_surat')
                    ->label('Tanggal Surat')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jabatan_ttd')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_ttd')
                    ->searchable(),
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
                Tables\Actions\Action::make('Unduh File')
                    ->icon('heroicon-o-arrow-down')
                    ->color('primary')
                    ->url(fn (SuratEdaran $record) => route('download.suratedaran',$record->id)),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

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
            'index' => Pages\ListSuratEdarans::route('/'),
            'create' => Pages\CreateSuratEdaran::route('/create'),
            'edit' => Pages\EditSuratEdaran::route('/{record}/edit'),
        ];
    }
}
