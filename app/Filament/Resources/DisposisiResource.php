<?php

namespace App\Filament\Resources;

use App\Filament\Pages\DisposisiSuratMasuk;
use App\Filament\Resources\DisposisiResource\Pages;
use App\Filament\Resources\DisposisiResource\RelationManagers;
use App\Models\Disposisi;
use App\Models\SuratMasuk;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section as InfolistSection;
use Filament\Resources\View;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DisposisiResource extends Resource
{

    protected static ?string $model = Disposisi::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Kelola Surat';

    protected static ?string $navigationLabel = 'Disposisi';

    protected static ?string $modelLabel = 'Disposisi';

    // public $disposisiId;

    public static function form(Form $form): Form
    {
        return $form

            ->schema([
                Section::make('Detail Surat Masuk')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                TextInput::make('surat_masuk_id')
                                    ->label('Nomor Agenda')
                                    ->disabled()
                                    ->default(fn () => request()->get('suratMasuk')),
                                TextInput::make('no_suratmasuk')
                                    ->label('Nomor Surat')
                                    ->disabled()
                                    ->default(fn () => SuratMasuk::find(request()->get('suratMasuk'))->no_suratmasuk ?? ''),
                                TextInput::make('tgl_suratmasuk')
                                    ->label('Tanggal Surat')
                                    ->disabled()
                                    ->default(fn () => SuratMasuk::find(request()->get('suratMasuk'))?->tgl_suratmasuk?->format('d-m-Y') ?? ''),
                                TextInput::make('pengirim')
                                    ->label('Pengirim')
                                    ->disabled()
                                    ->default(fn () => SuratMasuk::find(request()->get('suratMasuk'))->pengirim ?? ''),
                                TextInput::make('perihal')
                                    ->label('Perihal')
                                    ->disabled()
                                    ->default(fn () => SuratMasuk::find(request()->get('suratMasuk'))->perihal ?? ''),                        
                            ]),
                    ]),
                Forms\Components\Hidden::make('surat_masuk_id')
                    ->default(fn () => request()->get('suratMasuk')),    
                Forms\Components\TextInput::make('sifat')
                    ->required(),
                Forms\Components\TextInput::make('tindakan')
                    ->label('Tindakan')
                    // ->options([
                    //     'Tanggapan dan Saran' => 'Tanggapan dan Saran',
                    //     'Proses Lebih Lanjut' => 'Proses Lebih Lanjut',
                    //     'Koordinasi/Konfirmasikan' => 'Koordinasi/Konfirmasi',
                    //     'Hadir' => 'Hadiri',
                    // ])
                    ->required(),
                Forms\Components\TextInput::make('catatan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('pegawai')
                    ->label('Pegawai')
                    ->relationship('pegawai', 'nama')
                    ->multiple()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table

            ->columns([
                // Tables\Columns\TextColumn::make('suratMasuk.id')
                // ->label('Nomor Agenda')
                // ->numeric()
                // ->sortable(),
                Tables\Columns\TextColumn::make('suratMasuk.no_suratmasuk')
                    ->label('Nomor Surat')
                    ->sortable(),
                Tables\Columns\TextColumn::make('pegawai.nama')
                    ->label('Pegawai')
                    ->visible(fn () => auth()->user()?->hasAnyRole(['kabag', 'super_admin', 'admin']))
                    ->sortable(),
                Tables\Columns\TextColumn::make('sifat'),
                Tables\Columns\TextColumn::make('tindakan'),
                // Tables\Columns\TextColumn::make('catatan')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('suratMasuk.file_suratmasuk')
                    ->label('File Surat')
                    ->icon('heroicon-o-document-text') // Menambahkan ikon dokumen
                    ->formatStateUsing(fn ($state) => $state 
                        ? '<a href="' . asset('storage/' . $state) . '" target="_blank" style="color: blue; text-decoration: underline; justify-content: center;">Lihat File</a>' 
                        : 'Tidak ada file') // Mengubah tampilan teks
                    ->html()
                    ->sortable(),
            ])

            ->modifyQueryUsing(function (Builder $query) {
                $user = auth()->user();

                // Admin dan Kepala Bagian dapat melihat semua data
                if ($user->hasRole('super_admin') || $user->hasRole('admin')|| $user->hasRole('kabag')) {
                    return;
                } else {
                    return $query->whereHas('pegawai.user', function (Builder $query) {
                        $query->where('id', auth()->id());
                    });
                }
            })

            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('Lembar Disposisi')
                    ->icon('heroicon-o-arrow-down')
                    ->url(fn (Disposisi $record): string => route('download.disposisi', ['id' => $record->id]))
                    ->color('success'),
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
            'index' => Pages\ListDisposisis::route('/'),
            'create' => Pages\CreateDisposisi::route('/create'),
            'edit' => Pages\EditDisposisi::route('/{record}/edit'),
            //'view' => Pages\ViewDisposisi::route('/{record}'),
        ];
    }
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                InfolistSection::make('Detail Disposisi')->schema([
                    TextEntry::make('suratMasuk.no_suratmasuk')->label('Nomor Surat'),
                    TextEntry::make('suratMasuk.tgl_suratmasuk')
                    ->label('Tanggal Surat')
                    ->formatStateUsing(fn ($state) => $state ? \Carbon\Carbon::parse($state)->format('d-m-Y') : '-'),
                    TextEntry::make('suratMasuk.pengirim')->label('Pengirim'),
                    TextEntry::make('suratMasuk.perihal')->label('Perihal'),
                    TextEntry::make('catatan')->label('Catatan'),
                ]),
            ]);
    }
}
