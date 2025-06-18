<?php

namespace App\Filament\Resources\BiayaPerjadinResource\Pages;

use App\Filament\Resources\BiayaPerjadinResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBiayaPerjadin extends CreateRecord
{
    protected static string $resource = BiayaPerjadinResource::class;
    protected static bool $canCreateAnother = false;

    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
