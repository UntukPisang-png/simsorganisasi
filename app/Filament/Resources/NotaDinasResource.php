<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotaDinasResource\Pages;
use App\Filament\Resources\NotaDinasResource\Pages\CreateNotaDinas;
use App\Filament\Resources\NotaDinasResource\Pages\EditNotaDinas;
use App\Filament\Resources\NotaDinasResource\RelationManagers;
use App\Models\NotaDinas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NotaDinasResource extends Resource
{
    protected static ?string $model = NotaDinas::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Buat Surat';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('surat_keluar_id')
                    // ->disabled(fn ($livewire) => $livewire instanceof CreateNotaDinas)
                    // ->required(fn ($livewire) => $livewire instanceof EditNotaDinas)
                    ->disabled()
                    ->hint('Nomor agenda akan muncul setelah surat disimpan')
                    ->label('Nomor Agenda Surat Keluar'),
                Forms\Components\Textarea::make('kepada')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('dari')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('tembusan')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('no_surat')
                    ->label('Nomor Surat')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('tgl_surat')
                    ->label('Tanggal Surat')
                    ->required(),
                Forms\Components\TextInput::make('sifat')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('lampiran')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('perihal')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('isi_surat')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('detail_surat')
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
                Forms\Components\TextInput::make('pangkat')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('golongan')
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
                Tables\Columns\TextColumn::make('no_surat')
                    ->label('Nomor Surat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tgl_surat')
                    ->label('Tanggal Surat')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sifat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lampiran')
                    ->searchable(),
                Tables\Columns\TextColumn::make('perihal')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jabatan_ttd')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_ttd')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('pangkat')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('golongan')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('nip_ttd')
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
                    ->url(fn (NotaDinas $record) => route('download.notadinas',$record->id)),
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
            'index' => Pages\ListNotaDinas::route('/'),
            'create' => Pages\CreateNotaDinas::route('/create'),
            'edit' => Pages\EditNotaDinas::route('/{record}/edit'),
        ];
    }
}
