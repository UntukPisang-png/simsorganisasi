<?php

namespace App\Filament\Resources;

use App\Filament\Exports\SuratKeluarExporter;
use App\Filament\Resources\SuratKeluarResource\Pages;
use App\Filament\Resources\SuratKeluarResource\Pages\CreateSuratKeluar;
use App\Filament\Resources\SuratKeluarResource\Pages\EditSuratKeluar;
use App\Filament\Resources\SuratKeluarResource\RelationManagers;
use App\Models\SuratKeluar;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SuratKeluarResource extends Resource
{
    protected static ?string $model = SuratKeluar::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Kelola Surat';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                    ->label('Nomor Agenda')                    
                    ->hint('Nomor akan muncul ketika data sudah tersimpan')
                    ->disabled(fn ($livewire) => $livewire instanceof CreateSuratKeluar)
                    ->required(fn ($livewire) => $livewire instanceof EditSuratKeluar),
                    // ->default(fn (SuratKeluar $record) => $record->id ?? 'Auto-generated'),
                Forms\Components\TextInput::make('no_suratkeluar')
                    ->label('Nomor Surat')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('tgl_suratkeluar')
                    ->label('Tanggal Surat')
                    ->required(),
                Forms\Components\TextInput::make('perihal')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('lampiran')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('tujuan')
                    ->required()
                    ->maxLength(255),                
                Forms\Components\Select::make('kategori_id')
                    ->relationship('kategori', 'nama_kategori')
                    ->required(),
                Forms\Components\FileUpload::make('file_suratkeluar')
                    ->label('Upload File Surat')
                    ->helperText('Upload file surat dalam format PDF, DOC, atau DOCX (maksimal 5 MB)')
                    ->disk('public') // Simpan di storage/public
                    ->directory('surat_keluar') // Folder penyimpanan
                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                    ->maxSize(5120) // Maksimal 5 MB
                    // ->preserveFilenames() //Bisa digunakan jika nama file yang diupload berbeda dengan yang lain alias harus unique
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Nomor Agenda')
                    ->sortable(),                    
                Tables\Columns\TextColumn::make('no_suratkeluar')
                    ->label('Nomor Surat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tgl_suratkeluar')
                    ->label('Tanggal Surat')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('perihal')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('lampiran')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('tujuan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kategori.nama_kategori')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('file_suratkeluar')
                    ->label('File Surat')
                    ->icon('heroicon-o-document-text') // Menambahkan ikon dokumen
                    ->formatStateUsing(function ($state) {
                        // $url = asset('storage/surat_keluar/' . $state);
                        // $ext = pathinfo($state, PATHINFO_EXTENSION);
                        if (!$state) {
                            return 'Tidak ada file';
                        }
                        // Jika file PDF hasil upload (path di storage)
                        if (str_starts_with($state, 'surat_keluar/')) {
                            return '<a href="' . asset('storage/' . $state) . '" target="_blank" style="color: blue; text-decoration: underline;">Lihat File</a>';
                            // if ($ext === 'pdf') {
                            //     return '<a href="' . $url . '" target="_blank" style="color: blue; text-decoration: underline;">Lihat File</a>';
                            // } elseif ($ext === 'docx') {
                            //     return '<a href="' . $url . '" download style="color: green; text-decoration: underline;">Download File</a>';
                            // }
                        }
                        // Jika file docx dari route download (URL)
                        if (str_starts_with($state, 'http')) {
                            return '<a href="' . $state . '" target="_blank" style="color: blue; text-decoration: underline;">Lihat File</a>';
                        }
                        // Jika route download (misal: /surattugas/download/123)
                        // if (str_starts_with($state, '/')) {
                        //     return '<a href="' . url($state) . '" target="_blank" style="color: green; text-decoration: underline;">Download File</a>';
                        // }
                        return $state;
                    })    
                    ->html()
                    ->sortable(),
                // Tables\Columns\TextColumn::make('created_at')
                //     ->label('Dibuat Pada')
                //     ->dateTime()
                //     ->sortable(),
                    // ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                ExportAction::make()
                    ->exporter(SuratKeluarExporter::class),
                CreateAction::make()
                    ->label('Tambah Surat Keluar')
                    ->icon('heroicon-o-plus'),
            ])
            ->actions([
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
            'index' => Pages\ListSuratKeluars::route('/'),
            'create' => Pages\CreateSuratKeluar::route('/create'),
            'edit' => Pages\EditSuratKeluar::route('/{record}/edit'),
        ];
    }
}
