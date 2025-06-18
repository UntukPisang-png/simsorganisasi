<?php

namespace App\Filament\Resources\SuratMasukResource\Pages;

use App\Filament\Resources\SuratMasukResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords\Tab;
use Filament\Resources\Pages\ListRecords;

class ListSuratMasuks extends ListRecords
{
    protected static string $resource = SuratMasukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make()
            //     ->label('Tambah Surat Masuk')
            //     ->icon('heroicon-o-plus'),
        ];
    }

    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getTableQuery()->orderByDesc('created_at');
    }

    public function getTabs(): array
    {
        return [
            'Semua' => Tab::make()
                ->modifyQueryUsing(fn ($query) => $query->where('status_disposisi', '!=', 'dihapus')),
            'Belum Disposisi' => Tab::make()
                ->modifyQueryUsing(fn ($query) => $query->where('status_disposisi', 'Belum Disposisi')),
            'Sudah Disposisi' => Tab::make()
                ->modifyQueryUsing(fn ($query) => $query->where('status_disposisi', 'Sudah Disposisi')),
        ];
    }
}
