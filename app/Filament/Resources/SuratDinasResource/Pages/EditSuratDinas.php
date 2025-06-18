<?php

namespace App\Filament\Resources\SuratDinasResource\Pages;

use App\Filament\Resources\SuratDinasResource;
use App\Models\SuratKeluar;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSuratDinas extends EditRecord
{
    protected static string $resource = SuratDinasResource::class;

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
        $suratDinas = $this->record;
        $suratKeluar = $suratDinas->suratKeluar; 
        if ($suratKeluar){
            $suratKeluar->update([
            'no_suratkeluar' => $suratDinas->no_surat,
            'tgl_suratkeluar' => $suratDinas->tgl_surat,
            'perihal' => $suratDinas->perihal,
            'lampiran' => $suratDinas->lampiran,
            'tujuan' => $suratDinas->kepada,
            'kategori_id' => "5",
            'file_suratkeluar' => route('download.suratdinas', $suratDinas->id),
        ]);
        }
    }
}
