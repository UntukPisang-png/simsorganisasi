<?php

namespace App\Filament\Resources\SuratPengantarResource\Pages;

use App\Filament\Resources\SuratPengantarResource;
use App\Models\SuratKeluar;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSuratPengantar extends EditRecord
{
    protected static string $resource = SuratPengantarResource::class;

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
        $suratPengantar = $this->record;
        $suratKeluar = $suratPengantar->suratKeluar; 
        if ($suratKeluar){
            $suratKeluar->update([
            'no_suratkeluar' => $suratPengantar->no_surat,
            'tgl_suratkeluar' => $suratPengantar->tgl_surat,
            'tujuan' => $suratPengantar->kepada,
            'kategori_id' => "7",
            'file_suratkeluar' => route('download.suratpengantar', $suratPengantar->id),
        ]);
    }
    }
}
