<?php

namespace App\Filament\Resources\SuratEdaranResource\Pages;

use App\Filament\Resources\SuratEdaranResource;
use App\Models\SuratKeluar;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSuratEdaran extends CreateRecord
{
    protected static string $resource = SuratEdaranResource::class;
    protected static bool $canCreateAnother = false;

    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $suratEdaran = $this->record;
        $suratKeluar = SuratKeluar::create([
            'no_suratkeluar' => $suratEdaran->no_surat,
            'tgl_suratkeluar' => $suratEdaran->tgl_surat,
            'tujuan' => $suratEdaran->kepada,
            'kategori_id' => "6",
            'file_suratkeluar' => route('download.suratedaran', $suratEdaran->id),
        ]);
        $suratEdaran->surat_keluar_id = $suratKeluar->id;
        $suratEdaran->save();
    }

    
}
