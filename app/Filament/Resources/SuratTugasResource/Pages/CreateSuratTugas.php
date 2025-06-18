<?php

namespace App\Filament\Resources\SuratTugasResource\Pages;

use App\Filament\Resources\SuratTugasResource;
use App\Models\SuratKeluar;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Http;

class CreateSuratTugas extends CreateRecord
{
    protected static string $resource = SuratTugasResource::class;
    protected static bool $canCreateAnother = false;

    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    // protected function afterSave(): void
    // {
    //     dd('afterSave SuratTugas dijalankan');
    //     $suratTugas = $this->record;
    //     $suratKeluar = SuratKeluar::create([
    //         // 'id' => $suratTugas->surat_keluar_id,
    //         'no_suratkeluar' => $suratTugas->no_surat,
    //         'tgl_suratkeluar' => $suratTugas->tgl_surat,
    //         'tujuan' => $suratTugas->pegawai->pluck('nama')->join(', '),
    //         'kategori_id' => "8",
    //         'file_suratkeluar' => route('download.surattugas', $suratTugas->id),
    //     ]);
    //     $suratTugas->surat_keluar_id = $suratKeluar->id;
    //     $suratTugas->save();
    // }

    protected function afterCreate(): void
    {
        $surattugas = $this->record;

        $suratKeluar = SuratKeluar::create([
            // 'id' => $suratTugas->surat_keluar_id,
            'no_suratkeluar' => $surattugas->no_surat,
            'tgl_suratkeluar' => $surattugas->tgl_surat,
            'tujuan' => $surattugas->pegawai->pluck('nama')->join(', '),
            'kategori_id' => "8",
            'file_suratkeluar' => route('download.surattugas', $surattugas->id),
        ]);
        $surattugas->surat_keluar_id = $suratKeluar->id;
        $surattugas->save();

        $pegawais = $surattugas->pegawai;

        foreach ($pegawais as $pegawai) {
            $noHp = $pegawai->no_hp; // pastikan format: 628123456789
            $nama = $pegawai->nama;
            if ($noHp) {
                Http::withHeaders([
                    'Authorization' => 'dAh23LZBvCzY3D9BQT5S', // Ganti dengan API Key Fonnte Anda
                ])->asMultipart()->post('https://api.fonnte.com/send', [
                    [
                        'name' => 'target',
                        'contents' => $noHp,
                    ],
                    [
                        'name' => 'message',
                        'contents' => "Halo {$nama}, Anda menerima surat tugas dengan nomor *{$surattugas->no_surat}*. Mohon cek surat tugas anda di website, Terima kasih.",
                    ],
                    [
                        'name' => 'countryCode',
                        'contents' => '62',
                    ],
                ]);
            }
        }
    }
}
