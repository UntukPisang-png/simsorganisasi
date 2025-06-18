<?php

namespace App\Filament\Resources\LaporanKegiatanResource\Pages;

use App\Filament\Resources\LaporanKegiatanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLaporanKegiatan extends EditRecord
{
    protected static string $resource = LaporanKegiatanResource::class;

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

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['pegawai_id'] = auth()->user()->pegawai()->first()?->id;
        return $data;
    }

    // protected function afterSave(): void
    // {
    //     $laporanKegiatan = $this->record;
    //     $suratKeluar = $laporanKegiatan->suratKeluar; // relasi belongsTo di model laporanKegiatan

    //     if ($suratKeluar) {
    //         $suratKeluar->update([
    //             'no_suratkeluar' => $laporanKegiatan->no_surat,
    //             'tgl_suratkeluar' => $laporanKegiatan->tgl_surat,
    //             'tujuan' => $laporanKegiatan->pegawai->pluck('nama')->join(', '),
    //             'kategori_id' => "8",
    //             'file_suratkeluar' => route('download.laporanKegiatan', $laporanKegiatan->id),
    //         ]);
    //     }
    // }
}
