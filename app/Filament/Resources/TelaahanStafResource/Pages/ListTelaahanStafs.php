<?php

namespace App\Filament\Resources\TelaahanStafResource\Pages;

use App\Filament\Resources\TelaahanStafResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTelaahanStafs extends ListRecords
{
    protected static string $resource = TelaahanStafResource::class;

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
