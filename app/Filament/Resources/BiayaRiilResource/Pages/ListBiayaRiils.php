<?php

namespace App\Filament\Resources\BiayaRiilResource\Pages;

use App\Filament\Resources\BiayaRiilResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBiayaRiils extends ListRecords
{
    protected static string $resource = BiayaRiilResource::class;

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
}
