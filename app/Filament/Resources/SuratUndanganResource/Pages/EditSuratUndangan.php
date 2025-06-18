<?php

namespace App\Filament\Resources\SuratUndanganResource\Pages;

use App\Filament\Resources\SuratUndanganResource;
use App\Models\SuratKeluar;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSuratUndangan extends EditRecord
{
    protected static string $resource = SuratUndanganResource::class;

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
        $suratUndangan = $this->record;
        $suratKeluar = $suratUndangan->suratKeluar; 
        if ($suratKeluar){
            $suratKeluar->update([
            'no_suratkeluar' => $suratUndangan->no_surat,
            'tgl_suratkeluar' => $suratUndangan->tgl_surat,
            'perihal' => $suratUndangan->perihal,
            'lampiran' => $suratUndangan->lampiran,
            'tujuan' => $suratUndangan->kepada,
            'kategori_id' => "1",
            'file_suratkeluar' => route('download.suratundangan', $suratUndangan->id),
        ]);
    }
    }
}
