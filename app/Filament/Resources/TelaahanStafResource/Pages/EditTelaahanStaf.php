<?php

namespace App\Filament\Resources\TelaahanStafResource\Pages;

use App\Filament\Resources\TelaahanStafResource;
use App\Models\SuratKeluar;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTelaahanStaf extends EditRecord
{
    protected static string $resource = TelaahanStafResource::class;

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
        $telaahanStaf = $this->record;
        $suratKeluar = $telaahanStaf->suratKeluar; 
        if ($suratKeluar){
            $suratKeluar->update([
            'no_suratkeluar' => $telaahanStaf->no_surat,
            'tgl_suratkeluar' => $telaahanStaf->tgl_surat,
            'perihal' => $telaahanStaf->perihal,
            'lampiran' => $telaahanStaf->lampiran,
            'tujuan' => $telaahanStaf->kepada,
            'kategori_id' => "9",
            'file_suratkeluar' => route('download.telaahanstaf', $telaahanStaf->id),
        ]);
    }
    }
}
