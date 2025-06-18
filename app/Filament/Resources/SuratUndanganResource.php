<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuratUndanganResource\Pages;
use App\Filament\Resources\SuratUndanganResource\Pages\CreateSuratUndangan;
use App\Filament\Resources\SuratUndanganResource\Pages\EditSuratUndangan;
use App\Filament\Resources\SuratUndanganResource\RelationManagers;
use App\Http\Controllers\SuratUndanganController;
use App\Models\SuratUndangan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SuratUndanganResource extends Resource
{
    protected static ?string $model = SuratUndangan::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Buat Surat';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('surat_keluar_id')
                    // ->disabled(fn ($livewire) => $livewire instanceof CreateSuratUndangan)
                    // ->required(fn ($livewire) => $livewire instanceof EditSuratUndangan)
                    ->disabled()
                    ->hint('Nomor agenda akan muncul setelah surat disimpan')
                    ->label('Nomor Agenda Surat Keluar'),
                Forms\Components\TextInput::make('no_surat')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('lampiran')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('perihal')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('tgl_surat')
                    ->required(),
                Forms\Components\TextInput::make('kepada')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('di')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('isi_surat')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\DatePicker::make('tgl_undangan')
                    ->required(),
                Forms\Components\TextInput::make('tempat_undangan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('waktu_undangan')
                    ->required(),
                Forms\Components\TextInput::make('penutup')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('nama_ttd')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('jabatan_ttd')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nip_ttd')
                    ->required()
                    ->maxLength(20),
                // Forms\Components\Select::make('surat_keluar_id')
                //     ->relationship('suratKeluar', 'id')
                //     ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no_surat')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('lampiran')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('perihal')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tgl_surat')
                    ->label('Tanggal Surat')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kepada')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('di')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('tgl_undangan')
                    ->label('Tanggal Undangan')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tempat_undangan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('waktu_undangan')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('nama_ttd')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('jabatan_ttd')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('nip_ttd')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('suratKeluar.id')
                //     ->numeric()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('created_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\TextColumn::make('updated_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('Unduh File')
                    ->icon('heroicon-o-arrow-down')
                    ->color('primary')
                    ->url(fn (SuratUndangan $record) => route('download.suratundangan',$record->id)),
                Tables\Actions\ActionGroup::make([                
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])

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
            'index' => Pages\ListSuratUndangans::route('/'),
            'create' => Pages\CreateSuratUndangan::route('/create'),
            'edit' => Pages\EditSuratUndangan::route('/{record}/edit'),
        ];
    }
}
