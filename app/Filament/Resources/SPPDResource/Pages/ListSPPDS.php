<?php

namespace App\Filament\Resources\SPPDResource\Pages;

use App\Filament\Resources\SPPDResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords\Tab;
use Filament\Resources\Pages\ListRecords;

class ListSPPDS extends ListRecords
{
    protected static string $resource = SPPDResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\CreateAction::make(),
    //     ];
    // }

    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getTableQuery()->orderByDesc('created_at');
    }

    public function getTabs(): array
    {
        return [
            'Semua' => Tab::make()
                ->modifyQueryUsing(fn ($query) => $query->where('status_perjalanan', '!=', 'dihapus')),
            'Belum Berangkat' => Tab::make()
                ->modifyQueryUsing(fn ($query) => $query->where('status_perjalanan', 'Belum Berangkat')),
            'Dalam Perjalanan' => Tab::make()
                ->modifyQueryUsing(fn ($query) => $query->where('status_perjalanan', 'Dalam Perjalanan')),
            'Sudah Kembali' => Tab::make()
                ->modifyQueryUsing(fn ($query) => $query->where('status_perjalanan', 'Sudah Kembali')),
        ];
    }
}
