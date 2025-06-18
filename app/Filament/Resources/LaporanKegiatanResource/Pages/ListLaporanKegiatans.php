<?php

namespace App\Filament\Resources\LaporanKegiatanResource\Pages;

use App\Filament\Resources\LaporanKegiatanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLaporanKegiatans extends ListRecords
{
    protected static string $resource = LaporanKegiatanResource::class;

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
}
