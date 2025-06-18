<?php

namespace App\Filament\Resources\SuratUndanganResource\Pages;

use App\Filament\Resources\SuratUndanganResource;
use App\Models\SuratKeluar;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSuratUndangan extends CreateRecord
{
    protected static string $resource = SuratUndanganResource::class;
    protected static bool $canCreateAnother = false;

    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $suratUndangan = $this->record;
        $suratKeluar = SuratKeluar::create([
            'no_suratkeluar' => $suratUndangan->no_surat,
            'tgl_suratkeluar' => $suratUndangan->tgl_surat,
            'perihal' => $suratUndangan->perihal,
            'lampiran' => $suratUndangan->lampiran,
            'tujuan' => $suratUndangan->kepada,
            'kategori_id' => "1",
            'file_suratkeluar' => route('download.suratundangan', $suratUndangan->id),
        ]);
        $suratUndangan->surat_keluar_id = $suratKeluar->id;
        $suratUndangan->save();
    }
}
