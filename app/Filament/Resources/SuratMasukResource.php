<?php

namespace App\Filament\Resources;

use App\Enums\StatusDisposisi;
use App\Filament\Exports\SuratMasukExporter;
use App\Filament\Pages\DisposisiSuratMasuk;
use App\Filament\Resources\DisposisiResource\Pages\CreateDisposisi;
use App\Filament\Resources\SuratMasukResource\Pages;
use App\Filament\Resources\SuratMasukResource\RelationManagers;
use App\Models\Disposisi;
use App\Models\SuratMasuk;
use Filament\Actions\ActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\ExportAction;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\BadgeColumn;

class SuratMasukResource extends Resource
{
    protected static ?string $model = SuratMasuk::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    protected static ?string $navigationGroup = 'Kelola Surat';

    protected static ?string $navigationLabel = 'Surat Masuk';

    protected static ?string $modelLabel = 'Surat Masuk';

    protected static ?int $navigationSort = 2;

    public static function getPermissionPrefixes(): array
        {
            return [
                'view',
                'view_any',
                'create',
                'update',
                'restore',
                'restore_any',
                'replicate',
                'reorder',
                'delete',
                'delete_any',
                'force_delete',
                'force_delete_any',
                'disposisi',
            ];
        }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                    ->label('Nomor Agenda')
                    ->hint('Nomor agenda muncul setelah data disimpan')
                    ->disabled(),
                Forms\Components\TextInput::make('no_suratmasuk')
                    ->label('No Surat Masuk')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('tgl_suratmasuk')
                    ->label('Tanggal Surat')
                    ->required(),
                Forms\Components\DatePicker::make('tgl_diterima')
                    ->label('Tanggal Dikirim')
                    ->required(),
                Forms\Components\TextInput::make('pengirim')
                    ->label('Pengirim')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('perihal')
                    ->label('Perihal')
                    ->required()
                    ->maxLength(255),
                FileUpload::make('file_suratmasuk')
                    ->label('Upload File Surat')
                    ->helperText('Upload file surat masuk yang sudah discan dalam format PDF (maksimal 5 MB)')
                    ->disk('public') // Simpan di storage/public
                    ->directory('surat_masuk') // Folder penyimpanan
                    ->acceptedFileTypes(['application/pdf'])
                    ->maxSize(5120) // Maksimal 5 MB
                    // ->preserveFilenames() //Bisa digunakan jika nama file yang diupload berbeda dengan yang lain alias harus unique
                    ->required(),
                Forms\Components\Select::make('kategori_id')
                    ->relationship('kategori', 'nama_kategori')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Nomor Agenda')
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_suratmasuk')
                    ->label('Nomor Surat')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('tgl_suratmasuk')
                //     ->label('Tanggal Surat')
                //     ->date()
                //     ->sortable(),
                Tables\Columns\TextColumn::make('tgl_diterima')
                    ->label('Tanggal Dikirim')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pengirim')
                    ->searchable(),
                Tables\Columns\TextColumn::make('perihal')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kategori.nama_kategori')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('file_suratmasuk')
                    ->label('File Surat')
                    ->icon('heroicon-o-document-text') // Menambahkan ikon dokumen
                    ->formatStateUsing(fn ($state) => $state 
                        ? '<a href="' . asset('storage/' . $state) . '" target="_blank" style="color: blue; text-decoration: underline; justify-content: center;">Lihat File</a>' 
                        : 'Tidak ada file') // Mengubah tampilan teks
                    // ->visible(true)
                    ->html()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status_disposisi')
                    ->badge()
                    ->label('Status')
                    ->colors([
                        'success' => 'Sudah Disposisi',
                        'danger' => 'Belum Disposisi',
                    ])
                    ->icon(fn ($state) => $state === 'Sudah Disposisi' ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Belum Disposisi' => 'Belum Disposisi',
                        'Sudah Disposisi' => 'Sudah Disposisi',
                    ])
            ])
            ->actions([
                Tables\Actions\Action::make('Disposisi')
                    ->url(fn (SuratMasuk $record) => CreateDisposisi::getUrl([
                        'suratMasuk' => $record->id,
                        ]))
                    ->label('Disposisi')
                    ->icon('heroicon-o-arrow-right')
                    ->color('success')
                    ->visible(fn() => auth()->user()?->hasAnyRole(['kabag', 'super_admin'])),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->headerActions([
                ExportAction::make()
                    ->exporter(SuratMasukExporter::class),
                CreateAction::make()
                    ->label('Tambah Surat Masuk')
                    ->icon('heroicon-o-plus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    // public static function setSudahDisposisi(int $suratMasukId): void
    // {
    //     SuratMasuk::where('id', $suratMasukId)->update([
    //         'status_disposisi' => 'Sudah Disposisi',
    //     ]);
    // }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSuratMasuks::route('/'),
            'create' => Pages\CreateSuratMasuk::route('/create'),
            'edit' => Pages\EditSuratMasuk::route('/{record}/edit'),
        ];
    }
}
