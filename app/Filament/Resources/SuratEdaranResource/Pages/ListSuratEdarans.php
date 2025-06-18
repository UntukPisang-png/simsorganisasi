<?php

namespace App\Filament\Resources\SuratEdaranResource\Pages;

use App\Filament\Resources\SuratEdaranResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSuratEdarans extends ListRecords
{
    protected static string $resource = SuratEdaranResource::class;

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
