<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BiayaPerjadinResource\Pages;
use App\Filament\Resources\BiayaPerjadinResource\RelationManagers;
use App\Filament\Resources\BiayaRiilResource\Pages\CreateBiayaRiil;
use App\Models\BiayaPerjadin;
use App\Models\SPPD;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section as InfolistSection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BiayaPerjadinResource extends Resource
{
    protected static ?string $model = BiayaPerjadin::class;
    protected static ?string $navigationGroup = 'Kelola Biaya';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('s_p_p_d_id')
                    ->default(fn () => request()->get('sPPD')),
                Forms\Components\TextInput::make('nomor')
                    ->label('Nomor SPPD')
                    ->default(fn () => SPPD::find(request()->get('sPPD'))->nomor ?? '')
                    ->disabled(),
                Forms\Components\Hidden::make('pegawai_id')
                    ->default(fn () => SPPD::find(request()->get('sPPD'))->pegawai_id ?? ''),
                Forms\Components\TextInput::make('nama_pegawai')
                    ->label('Nama Pegawai')
                    ->default(fn () => SPPD::find(request()->get('sPPD'))->pegawai->nama ?? '')
                    ->disabled(),
                Forms\Components\TextInput::make('lama_perjadin')
                    ->label('Lama Perjalanan Dinas (SPPD)')
                    ->default(fn () => SPPD::find(request()->get('sPPD'))->lama_perjadin ?? '')
                    ->disabled(),                        
                Forms\Components\TextInput::make('uang_harian')
                    ->hint('Contoh: 500000')
                    ->prefix('Rp')
                    ->numeric()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Repeater::make('transportasi')
                    ->label('Transportasi')
                    ->schema([
                        Forms\Components\TextInput::make('berangkat_tujuan')
                            ->label('Berangkat ke Tujuan')
                            ->hint('Contoh: Banjarmasin - Jakarta, Bandara-Tujuan, dll.')
                            ->required(),
                        Forms\Components\TextInput::make('biaya')
                            ->label('Biaya Transportasi')
                            ->hint('Contoh: 500000')
                            ->prefix('Rp')
                            ->numeric()
                            ->required(),
                    ])
                    ->minItems(1)
                    ->columnSpanFull(),
                Forms\Components\Repeater::make('penginapan')
                    ->schema([
                        Forms\Components\TextInput::make('jenis_penginapan')
                            ->label('Jenis Penginapan')
                            ->hint('Contoh: Hotel, Homestay, dll.')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('biaya')
                            ->label('Biaya Penginapan Per Satuan')
                            ->hint('Contoh: 500000')
                            ->reactive()
                            ->prefix('Rp')
                            ->numeric()
                            // ->afterStateUpdated(function (Set $set, $state, Get $get) {
                            //     $lama = (int) $get('lamanya');
                            //     $total = $state * $lama;
                            //     $set('total_penginapan', intval($total));
                            // })
                            ->required(),
                        Forms\Components\TextInput::make('lamanya')
                            ->label('Lamanya Menginap')
                            ->reactive()
                            ->hint('Contoh: 3')                            
                            ->numeric()
                            ->afterStateUpdated(function (Set $set, $state, Get $get) {
                                $biaya = (int) $get('biaya');
                                $total = $state * $biaya;
                                $set('total_penginapan', $total);
                            })
                            ->required(),
                        Forms\Components\TextInput::make('satuan')
                            ->label('Satuan')
                            ->hint('Contoh: Malam, Hari, dll.')
                            ->required(),                        
                        Forms\Components\TextInput::make('total_penginapan')
                            // ->default(function (Get $get) {
                            //     $biaya = (int) $get('biaya');
                            //     $lama = (int) $get('lamanya');
                            //     return intval($biaya * $lama);
                            // })
                            ->readOnly()
                            ->label('Total Biaya Penginapan')
                            ->prefix('Rp'),
                    ])
                    ->minItems(1)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID Perjadin')
                    ->sortable(), 
                Tables\Columns\TextColumn::make('sPPD.nomor')
                    ->label('Nomor SPPD')
                    ->sortable(),  
                Tables\Columns\TextColumn::make('uang_harian')
                    ->prefix('Rp')
                    ->numeric()
                    ->searchable(),
                Tables\Columns\TextColumn::make('pegawai.nama')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_perjadin')
                    ->prefix('Rp')
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
                Tables\Actions\Action::make('Buat Biaya Riil')
                        ->url(fn (BiayaPerjadin $record) => CreateBiayaRiil::getUrl([
                            'biayaPerjadin' => $record->id,
                            ]))
                        ->icon('heroicon-o-arrow-right')
                        ->color('success'),
                Tables\Actions\Action::make('Unduh Rincian Biaya')
                        ->icon('heroicon-o-arrow-down')
                        ->color('primary')
                        ->url(fn (BiayaPerjadin $record) => route('download.rincianbiaya',$record->id)),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
                

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
            'index' => Pages\ListBiayaPerjadins::route('/'),
            'create' => Pages\CreateBiayaPerjadin::route('/create'),
            'edit' => Pages\EditBiayaPerjadin::route('/{record}/edit'),
        //'view' => Pages\ViewBiayaPerjadin::route('/{record}'),
        ];
    }
    // public static function infolist(Infolist $infolist): Infolist
    // {
    //     return $infolist
    //         ->schema([
    //             InfolistSection::make('Detail Perjadin')->schema([
    //                 TextEntry::make('sPPD.nomor')->label('Nomor Surat'),
    //                 TextEntry::make('sPPD.tgl_berangkat')->label('Tanggal Berangkat')
    //                 ->formatStateUsing(fn ($state) => $state ? \Carbon\Carbon::parse($state)->format('d-m-Y') : '-'),
    //                 TextEntry::make('sPPD.tgl_kembali')->label('Tanggal Kembali')
    //                 ->formatStateUsing(fn ($state) => $state ? \Carbon\Carbon::parse($state)->format('d-m-Y') : '-'),
    //                 TextEntry::make('sPPD.lama_perjadin')->label('Lama Perjadin'),
    //             ]),
    //         ]);
    // }
}
