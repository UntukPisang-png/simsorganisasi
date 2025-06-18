<?php

namespace App\Filament\Resources\SuratDinasResource\Pages;

use App\Filament\Resources\SuratDinasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSuratDinas extends ListRecords
{
    protected static string $resource = SuratDinasResource::class;

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
