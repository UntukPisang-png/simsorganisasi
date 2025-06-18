<?php

namespace App\Filament\Resources\BiayaPerjadinResource\Pages;

use App\Filament\Resources\BiayaPerjadinResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBiayaPerjadins extends ListRecords
{
    protected static string $resource = BiayaPerjadinResource::class;

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
