<?php

namespace App\Filament\Resources\BiayaRiilResource\Pages;

use App\Filament\Resources\BiayaRiilResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBiayaRiil extends CreateRecord
{
    protected static string $resource = BiayaRiilResource::class;
    protected static bool $canCreateAnother = false;

    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
