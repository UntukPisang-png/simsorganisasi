<?php

namespace App\Filament\Resources\TelaahanStafResource\Pages;

use App\Filament\Resources\TelaahanStafResource;
use App\Models\SuratKeluar;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTelaahanStaf extends CreateRecord
{
    protected static string $resource = TelaahanStafResource::class;
    protected static bool $canCreateAnother = false;

    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $telaahanStaf = $this->record;
        $suratKeluar = SuratKeluar::create([
            'no_suratkeluar' => $telaahanStaf->no_surat,
            'tgl_suratkeluar' => $telaahanStaf->tgl_surat,
            'perihal' => $telaahanStaf->perihal,
            'lampiran' => $telaahanStaf->lampiran,
            'tujuan' => $telaahanStaf->kepada,
            'kategori_id' => "9",
            'file_suratkeluar' => route('download.telaahanstaf', $telaahanStaf->id),
        ]);
        $telaahanStaf->surat_keluar_id = $suratKeluar->id;
        $telaahanStaf->save();
    }
}
