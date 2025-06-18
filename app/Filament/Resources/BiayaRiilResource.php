<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BiayaRiilResource\Pages;
use App\Filament\Resources\BiayaRiilResource\RelationManagers;
use App\Models\BiayaRiil;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BiayaRiilResource extends Resource
{
    protected static ?string $model = BiayaRiil::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Kelola Biaya';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Repeater::make('pengeluaran')
                    ->schema([
                        Forms\Components\Select::make('template_sumber')
                            ->label('Pilih Data Dari Rincian Biaya Perjadin')
                            ->hint('Jika tidak ada, isi secara manual')
                            ->options(function ($get) {
                                $options = []; // Inisialisasi array kosong
                                $biayaPerjadin = \App\Models\BiayaPerjadin::find($get('../../biaya_perjadin_id'));
                                if ($biayaPerjadin) {
                                    foreach ($biayaPerjadin->penginapan ?? [] as $i => $item) {
                                        $options["penginapan_$i"] = 'Penginapan: ' . ($item['jenis_penginapan'] ?? 'Penginapan ' . ($i + 1));
                                    }
                                    foreach ($biayaPerjadin->transportasi ?? [] as $i => $item) {
                                        $options["transportasi_$i"] = 'Transportasi: ' . ($item['berangkat_tujuan'] ?? 'Transportasi ' . ($i + 1));
                                    }
                                }
                                return $options;
                            })
                            // ->default('manual')
                            ->reactive()
                            ->afterStateUpdated(function ($state, $set, $get) {
                                $biayaPerjadin = \App\Models\BiayaPerjadin::find($get('../../biaya_perjadin_id'));
                                if (!$biayaPerjadin) return;

                                if (str_starts_with($state, 'penginapan_')) {
                                    $idx = (int)str_replace('penginapan_', '', $state);
                                    $item = $biayaPerjadin->penginapan[$idx] ?? [];
                                    $set('nama_pengeluaran', $item['jenis_penginapan'] ?? '');
                                    $set(
                                        'keterangan',
                                        ($item['lamanya'] ?? '') . ' ' .
                                        ($item['satuan'] ?? '') . ' x Rp' .
                                        number_format($item['biaya'] ?? 0, 0, ',', '.')
                                    );
                                    $set('jumlah', $item['total_penginapan'] ?? '');
                                } elseif (str_starts_with($state, 'transportasi_')) {
                                    $idx = (int)str_replace('transportasi_', '', $state);
                                    $item = $biayaPerjadin->transportasi[$idx] ?? [];
                                    $set('nama_pengeluaran', '');
                                    $set('keterangan', $item['berangkat_tujuan'] ?? '');
                                    $set('jumlah', $item['biaya'] ?? '');
                                } else {
                                    $set('nama_pengeluaran', '');
                                    $set('keterangan', '');
                                    $set('jumlah', '');
                                }
                            }),

                        Forms\Components\TextInput::make('nama_pengeluaran')
                            ->hint('Contoh: Transportasi, Akomodasi, Makan, dll.')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('keterangan')
                            ->label('Keterangan')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('jumlah')
                            ->label('Jumlah Biaya')
                            ->numeric()
                            ->required()
                            ->maxLength(255)
                            ->prefix('Rp'),
                    ])
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Hidden::make('biaya_perjadin_id')
                    ->default(fn () => request()->get('biayaPerjadin'))
                    ->required(),

                Forms\Components\Hidden::make('s_p_p_d_id')
                    ->label('Pilih SPPD')
                    ->required()
                    ->default(function ($get) {
                        $biayaPerjadinId = $get('biaya_perjadin_id');
                        if ($biayaPerjadinId) {
                            $biayaPerjadin = \App\Models\BiayaPerjadin::find($biayaPerjadinId);
                            return $biayaPerjadin?->s_p_p_d_id;
                        }
                        return null;
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sPPD.nomor')
                    ->label('Nomor SPPD')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('biayaPerjadin.id')
                    ->label('Biaya Perjadin ID')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_pengeluaran') //hitung di model biayariil
                    ->prefix('Rp')                 
                    ->numeric()   
                    ->label('Total Pengeluaran'),
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
                    ->url(fn (BiayaRiil $record) => route('download.biayariil',$record->id)),
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
            'index' => Pages\ListBiayaRiils::route('/'),
            'create' => Pages\CreateBiayaRiil::route('/create'),
            'edit' => Pages\EditBiayaRiil::route('/{record}/edit'),
        ];
    }
}
