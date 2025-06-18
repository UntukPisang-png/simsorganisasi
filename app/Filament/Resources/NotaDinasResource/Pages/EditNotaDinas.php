<?php

namespace App\Filament\Resources\NotaDinasResource\Pages;

use App\Filament\Resources\NotaDinasResource;
use App\Models\NotaDinas;
use App\Models\SuratKeluar;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNotaDinas extends EditRecord
{
    protected static string $resource = NotaDinasResource::class;

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

    protected function afterSave(): void
    {
        $notaDinas = $this->record;
        $suratKeluar = $notaDinas->suratKeluar; 
        if ($suratKeluar){
            $suratKeluar->update([
                'no_suratkeluar' => $notaDinas->no_surat,
                'tgl_suratkeluar' => $notaDinas->tgl_surat,
                'perihal' => $notaDinas->perihal,
                'lampiran' => $notaDinas->lampiran,
                'tujuan' => $notaDinas->kepada,
                'kategori_id' => "4",
                'file_suratkeluar' => route('download.notadinas', $notaDinas->id),
            ]);
        }
    }
}
