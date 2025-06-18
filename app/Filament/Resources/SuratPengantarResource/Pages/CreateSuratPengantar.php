<?php

namespace App\Filament\Resources\SuratPengantarResource\Pages;

use App\Filament\Resources\SuratPengantarResource;
use App\Models\SuratKeluar;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSuratPengantar extends CreateRecord
{
    protected static string $resource = SuratPengantarResource::class;
    protected static bool $canCreateAnother = false;

    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $suratPengantar = $this->record;
        $suratKeluar = SuratKeluar::create([
            'no_suratkeluar' => $suratPengantar->no_surat,
            'tgl_suratkeluar' => $suratPengantar->tgl_surat,
            'tujuan' => $suratPengantar->kepada,
            'kategori_id' => "7",
            'file_suratkeluar' => route('download.suratpengantar', $suratPengantar->id),
        ]);
        $suratPengantar->surat_keluar_id = $suratKeluar->id;
        $suratPengantar->save();
    }
}
