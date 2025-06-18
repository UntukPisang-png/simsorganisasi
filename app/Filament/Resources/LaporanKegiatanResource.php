<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LaporanKegiatanResource\Pages;
use App\Filament\Resources\LaporanKegiatanResource\RelationManagers;
use App\Models\LaporanKegiatan;
use App\Models\SuratTugas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LaporanKegiatanResource extends Resource
{
    protected static ?string $model = LaporanKegiatan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Buat Surat';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('surat_tugas_id')
                    ->hint("Pilih nomor jika Laporan ini merupakan tindak lanjut dari surat tugas")
                    ->relationship('suratTugas', 'no_surat')
                    ->options(SuratTugas::pluck('no_surat', 'id')->toArray())
                    ->searchable(),
                Forms\Components\Hidden::make('pegawai_id')
                    ->default(fn () => auth()->user()->pegawai()->first()?->id),
                Forms\Components\TextInput::make('no_laporan')
                    ->label("Nomor Laporan")
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('umum')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('landasan')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('maksud')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('kegiatan')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('hasil')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('kesimpulan')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('penutup')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('paraf')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('jabatan_ttd')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nama_ttd')
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
                Tables\Columns\TextColumn::make('no_laporan')
                    ->label("Nomor laporan")
                    ->searchable(),
                Tables\Columns\TextColumn::make('suratTugas.no_surat')
                    ->label('Dari Surat Tugas')
                    ->sortable(),
                Tables\Columns\TextColumn::make('pegawai.nama') // Tambahkan ini
                    ->label('Pembuat')
                    ->visible(fn() => auth()->user()?->hasAnyRole(['kepegawaian', 'kabag', 'super_admin']))
                    ->searchable(),
                // Tables\Columns\TextColumn::make('jabatan_ttd')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('nama_ttd')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('nip_ttd')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                $user = auth()->user();
                // Jika bukan kepegawaian, kabag, super_admin, atau admin, hanya tampilkan data milik sendiri
                if (! $user->hasAnyRole(['kepegawaian', 'kabag', 'super_admin'])) {
                    $query->whereHas('pegawai.user', function (Builder $query) use ($user) {
                        $query->where('id', $user->id);
                    });
                }
            })
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('Unduh File')
                    ->icon('heroicon-o-arrow-down')
                    ->color('primary')
                    ->url(fn (LaporanKegiatan $record) => route('download.laporankegiatan',$record->id)),
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
            'index' => Pages\ListLaporanKegiatans::route('/'),
            'create' => Pages\CreateLaporanKegiatan::route('/create'),
            'edit' => Pages\EditLaporanKegiatan::route('/{record}/edit'),
        ];
    }
}
