<?php

namespace App\Filament\Resources\BiayaRiilResource\Pages;

use App\Filament\Resources\BiayaRiilResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBiayaRiil extends EditRecord
{
    protected static string $resource = BiayaRiilResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }

    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
