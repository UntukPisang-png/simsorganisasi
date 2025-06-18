<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BiayaPerjadinResource\Pages\CreateBiayaPerjadin;
use App\Filament\Resources\SPPDResource\Pages;
use App\Filament\Resources\SPPDResource\Pages\CreateSPPD;
use App\Filament\Resources\SPPDResource\Pages\EditSPPD;
use App\Filament\Resources\SPPDResource\RelationManagers;
use App\Models\SPPD;
use App\Models\SuratTugas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\CreateAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SPPDResource extends Resource
{
    protected static ?string $model = SPPD::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'SPPD';
    protected static ?string $navigationGroup = 'Kelola Biaya';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\TextInput::make('surat_keluar_id')
                //     // ->disabled(fn ($livewire) => $livewire instanceof CreateSPPD)
                //     // ->required(fn ($livewire) => $livewire instanceof EditSPPD)
                //     ->disabled()
                //     ->hint('Nomor agenda akan muncul setelah surat disimpan')
                //     ->label('Nomor Agenda Surat Keluar'),
                Forms\Components\Hidden::make('surat_tugas_id')
                    ->default(fn () => request()->get('suratTugas')), 
                Forms\Components\TextInput::make('no_surat')
                    ->label('Nomor Surat Tugas')
                    ->disabled()
                    ->default(fn () => SuratTugas::find(request()->get('suratTugas'))->no_surat ?? ''),
                Forms\Components\Hidden::make('nomor')
                    ->default(fn () => SuratTugas::find(request()->get('suratTugas'))->no_surat ?? ''),
                // Forms\Components\TextInput::make('nomor')
                //     ->label('Nomor SPPD')
                //     ->readOnly()
                //     ->default(fn () => SuratTugas::find(request()->get('suratTugas'))->no_surat ?? ''),
                Forms\Components\TextInput::make('perintah_dari')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('pegawai_id')
                    ->label('Pegawai')
                    ->options(function ($get, $record) {
                        $suratTugasId = $get('surat_tugas_id') ?? request()->get('suratTugas') ?? $record?->surat_tugas_id;
                        if ($suratTugasId) {
                            $suratTugas = \App\Models\SuratTugas::find($suratTugasId);
                            if ($suratTugas) {
                                return $suratTugas->pegawai->pluck('nama', 'id');
                            }
                        }
                        return \App\Models\Pegawai::pluck('nama', 'id');
                    })
                    ->required(),                
                Forms\Components\Textarea::make('maksud')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('berangkat')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('tujuan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('angkutan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('lama_perjadin')
                    ->label('Lama Perjalanan Dinas')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('tgl_berangkat')
                    ->label('Tanggal Berangkat')
                    ->required(),
                Forms\Components\DatePicker::make('tgl_kembali')
                    ->label('Tanggal Kembali')
                    ->required(),
                Forms\Components\Repeater::make('pengikut')
                    ->label('Pengikut Perjalanan Dinas')
                    ->hint('Isi data pengikut perjalanan dinas jika ada.')
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Pengikut')
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('tgl_lahir')
                            ->label('Tanggal Lahir Pengikut'),
                        Forms\Components\Textarea::make('keterangan')
                            ->label('Keterangan'),
                    ])
                    ->minItems(1)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('bebasbiaya_instansi')
                    ->label('Bebas Biaya Instansi')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('bebasbiaya_akun')
                    ->label('Bebas Biaya Akun')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('keterangan')
                    ->columnSpanFull(),
                Forms\Components\DatePicker::make('tgl_surat')
                    ->label('Tanggal Surat')
                    ->required(),
                Forms\Components\Textarea::make('catatan_lembar2')
                    ->label('Catatan Lain-lain (Lembar 2)')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('jabatan_ttd')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nama_ttd')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('pangkat_ttd')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nip_ttd')
                    ->required()
                    ->maxLength(20),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('suratTugas.no_surat')
                    ->label('Nomor Surat Tugas')
                    ->sortable(),
                // Tables\Columns\TextColumn::make('nomor')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('perintah_dari')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('pegawai.nama')
                    ->label('Pegawai yang melaksanakan')
                    ->sortable()
                    ->visible(fn () => auth()->user()?->hasAnyRole(['kabag', 'super_admin', 'admin', 'kepegawaian', 'keuangan'])),
                             
                // Tables\Columns\TextColumn::make('berangkat')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('tujuan')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('angkutan')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('lama_perjadin')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('tgl_berangkat')
                    ->label('Tanggal Berangkat')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tgl_kembali')
                    ->label('Tanggal Kembali')
                    ->date()
                    ->sortable(),
                // Tables\Columns\TextColumn::make('bebasbiaya_instansi')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('bebasbiaya_akun')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('tgl_surat')
                    ->label('Tanggal Surat')
                    ->date()
                    ->sortable(),
                // Tables\Columns\TextColumn::make('jabatan_ttd')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('nama_ttd')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('pangkat_ttd')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('nip_ttd')
                //     ->searchable(),
                Tables\Columns\SelectColumn::make('status_perjalanan')
                    ->label('Status')
                    ->options([
                        'Belum Berangkat' => 'Belum Berangkat',
                        'Dalam Perjalanan' => 'Dalam Perjalanan',
                        'Sudah Kembali' => 'Sudah Kembali',
                    ])
                    ->sortable()
                    ->disabled(fn () => !auth()->user()?->hasAnyRole(['kepegawaian', 'super_admin'])),
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
            ->modifyQueryUsing(function (Builder $query) {
                $user = auth()->user();

                // Admin dan Kepala Bagian dapat melihat semua data
                if ($user->hasRole('super_admin') || $user->hasRole('keuangan') || $user->hasRole('kepegawaian') || $user->hasRole('kabag')) {
                    return;
                } else {
                    return $query->whereHas('pegawai.user', function (Builder $query) {
                        $query->where('id', auth()->id());
                    });
                }
            })
            ->actions([
                Tables\Actions\Action::make('Buat Biaya Perjadin')
                        ->url(fn (SPPD $record) => CreateBiayaPerjadin::getUrl([
                            'sPPD' => $record->id,
                            ]))
                        ->visible(fn() => auth()->user()?->hasAnyRole(['keuangan', 'super_admin']))
                        ->label('Buat Biaya Perjadin')    
                        ->icon('heroicon-o-arrow-right')
                        ->color('success'),
                Tables\Actions\Action::make('Unduh File')
                        ->icon('heroicon-o-arrow-down')
                        ->color('primary')
                        ->url(fn (SPPD $record) => route('download.SPPD',$record->id)),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
                

            ])
            // ->headerActions([
            //     CreateAction::make()
            //         ->label('Tambah SPPD')
            //         ->icon('heroicon-o-plus'),
            // ])
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
            'index' => Pages\ListSPPDS::route('/'),
            'create' => Pages\CreateSPPD::route('/create'),
            'edit' => Pages\EditSPPD::route('/{record}/edit'),
        ];
    }
}
