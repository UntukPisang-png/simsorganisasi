<?php

namespace App\Filament\Resources\LaporanKegiatanResource\Pages;

use App\Filament\Resources\LaporanKegiatanResource;
use App\Models\SuratKeluar;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLaporanKegiatan extends CreateRecord
{
    protected static string $resource = LaporanKegiatanResource::class;
    protected static bool $canCreateAnother = false;

    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['pegawai_id'] = auth()->user()->pegawai()->first()?->id;
        return $data;
    }

    // protected function afterCreate(): void
    // {
    //     $laporanKegiatan = $this->record;
    //     $suratKeluar = SuratKeluar::create([
    //         'no_suratkeluar' => $laporanKegiatan->no_laporan,
    //         'tgl_suratkeluar' => $laporanKegiatan->tgl_surat,
    //         // 'tujuan' => 
    //         'kategori_id' => "8",
    //         'file_suratkeluar' => route('download.laporanKegiatan', $laporanKegiatan->id),
    //     ]);
    //     $laporanKegiatan->surat_keluar_id = $suratKeluar->id;
    //     $laporanKegiatan->save();
    // }

    
}
