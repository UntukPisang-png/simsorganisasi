<?php

namespace App\Filament\Resources\SuratEdaranResource\Pages;

use App\Filament\Resources\SuratEdaranResource;
use App\Models\SuratKeluar;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSuratEdaran extends EditRecord
{
    protected static string $resource = SuratEdaranResource::class;

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
        $suratEdaran = $this->record;
        $suratKeluar = $suratEdaran->suratKeluar; 
        if ($suratKeluar){
            $suratKeluar->update([
            'no_suratkeluar' => $suratEdaran->no_surat,
            'tgl_suratkeluar' => $suratEdaran->tgl_surat,
            'tujuan' => $suratEdaran->kepada,
            'kategori_id' => "6",
            'file_suratkeluar' => route('download.suratedaran', $suratEdaran->id),
        ]);
    }
    }
}
