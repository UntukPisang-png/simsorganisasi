<?php

namespace App\Filament\Resources\SuratTugasResource\Pages;

use App\Filament\Resources\SuratTugasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords\Tab;
use Filament\Resources\Pages\ListRecords;

class ListSuratTugas extends ListRecords
{
    protected static string $resource = SuratTugasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getTableQuery()->orderByDesc('created_at');
    }

    // public function getTabs(): array
    // {
    //     return [
    //         'Semua' => Tab::make()
    //             ->modifyQueryUsing(fn ($query) => $query->where('status_bertugas', '!=', 'dihapus')),
    //         'Belum Berangkat' => Tab::make()
    //             ->modifyQueryUsing(fn ($query) => $query->where('status_bertugas', 'Belum Berangkat')),
    //         'Melaksanakan Tugas' => Tab::make()
    //             ->modifyQueryUsing(fn ($query) => $query->where('status_bertugas', 'Melaksanakan Tugas')),
    //         'Selesai' => Tab::make()
    //             ->modifyQueryUsing(fn ($query) => $query->where('status_bertugas', 'Selesai')),
    //     ];
    // }
}
