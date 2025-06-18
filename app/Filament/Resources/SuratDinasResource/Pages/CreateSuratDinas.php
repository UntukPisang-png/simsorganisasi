<?php

namespace App\Filament\Resources\SuratDinasResource\Pages;

use App\Filament\Resources\SuratDinasResource;
use App\Models\SuratKeluar;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSuratDinas extends CreateRecord
{
    protected static string $resource = SuratDinasResource::class;
    protected static bool $canCreateAnother = false;

    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $suratDinas = $this->record;
        $suratKeluar = SuratKeluar::create([
            'no_suratkeluar' => $suratDinas->no_surat,
            'tgl_suratkeluar' => $suratDinas->tgl_surat,
            'perihal' => $suratDinas->perihal,
            'lampiran' => $suratDinas->lampiran,
            'tujuan' => $suratDinas->kepada,
            'kategori_id' => "5",
            'file_suratkeluar' => route('download.suratdinas', $suratDinas->id),
        ]);
        $suratDinas->surat_keluar_id = $suratKeluar->id;
        $suratDinas->save();
    }

}
