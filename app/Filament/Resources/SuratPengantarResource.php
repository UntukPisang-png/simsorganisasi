<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuratPengantarResource\Pages;
use App\Filament\Resources\SuratPengantarResource\Pages\CreateSuratPengantar;
use App\Filament\Resources\SuratPengantarResource\Pages\EditSuratPengantar;
use App\Filament\Resources\SuratPengantarResource\RelationManagers;
use App\Models\SuratPengantar;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SuratPengantarResource extends Resource
{
    protected static ?string $model = SuratPengantar::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Buat Surat';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('surat_keluar_id')
                    // ->disabled(fn ($livewire) => $livewire instanceof CreateSuratPengantar)
                    // ->required(fn ($livewire) => $livewire instanceof EditSuratPengantar)
                    ->disabled()
                    ->hint('Nomor agenda akan muncul setelah surat disimpan')
                    ->label('Nomor Agenda Surat Keluar'),
                Forms\Components\TextInput::make('no_surat')
                    ->label('Nomor Surat')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('tgl_surat')
                    ->label('Tanggal Surat')
                    ->required(),
                Forms\Components\TextInput::make('kepada')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('di')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Repeater::make('detail_naskah')
                    ->label('Detail Naskah Dinas')
                    ->schema([
                        Forms\Components\TextInput::make('naskah_dinas')
                            ->label('Naskah Dinas/Barang Yang Dikirimkan')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('jumlah')
                            ->label('Jumlah')
                            ->required(),
                        Forms\Components\Textarea::make('keterangan')
                            ->label('Keterangan')
                            ->required(),
                    ])
                    ->minItems(1)
                    ->columnSpanFull(),
                Forms\Components\DatePicker::make('tgl_diterima')
                    ->label('Tanggal Diterima')
                    ->required(),
                Forms\Components\Repeater::make('penerima')
                    ->label('Penerima')
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Penerima')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('jabatan')
                            ->label('Jabatan Penerima')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('pangkat_golongan')
                            ->label('Pangkat dan Golongan Penerima')
                            ->hint('Contoh: Pembina Utama Muda/IVd')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('nip')
                            ->label('NIP Penerima')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->maxItems(1)
                    ->columnSpanFull(),
                Forms\Components\Select::make('pegawai_id')
                    ->label('Pegawai Pengirim')
                    ->relationship('pegawai', 'nama')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no_surat')
                    ->label('Nomor Surat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tgl_surat')
                    ->label('Tanggal Surat')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kepada')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('di')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('detail_naskah.naskah_dinas')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('detail_naskah.jumlah')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('detail_naskah.keterangan')
                //     ->searchable(),                                        
                Tables\Columns\TextColumn::make('penerima')
                    ->getStateUsing(fn ($record) => collect($record->penerima)->first()['nama'] ?? '-')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pegawai.nama')
                    ->label('Pengirim')
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
                    ->url(fn (SuratPengantar $record) => route('download.suratpengantar',$record->id)),
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
            'index' => Pages\ListSuratPengantars::route('/'),
            'create' => Pages\CreateSuratPengantar::route('/create'),
            'edit' => Pages\EditSuratPengantar::route('/{record}/edit'),
        ];
    }
}
