<?php

namespace App\Filament\Resources\SuratTugasResource\Pages;

use App\Filament\Resources\SuratTugasResource;
use App\Models\SuratKeluar;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Http;

class EditSuratTugas extends EditRecord
{
    protected static string $resource = SuratTugasResource::class;

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
        $suratTugas = $this->record;
        $suratKeluar = $suratTugas->suratKeluar; // relasi belongsTo di model SuratTugas

        if ($suratKeluar) {
            $suratKeluar->update([
                'no_suratkeluar' => $suratTugas->no_surat,
                'tgl_suratkeluar' => $suratTugas->tgl_surat,
                'tujuan' => $suratTugas->pegawai->pluck('nama')->join(', '),
                'kategori_id' => "8",
                'file_suratkeluar' => route('download.surattugas', $suratTugas->id),
            ]);
        }

        $pegawais = $suratTugas->pegawai;

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
                        'contents' => "Halo {$nama}, terdapat perubahan pada surat tugas anda yang bernomor *{$suratTugas->no_surat}*. Mohon cek kembali surat tugas anda di website, Terima kasih.",
                    ],
                    [
                        'name' => 'countryCode',
                        'contents' => '62',
                    ],
                ]);
            }
        }
    }

    // protected function afterCreate(): void
    // {
    //     $surattugas = $this->record;
        
    //     }
    // }
}

//"Halo {$nama}, terdapat perubahan pada surat tugas anda yang bernomor *{$surattugas->no_surat}*. Mohon cek kembali surat tugas anda di website, Terima kasih.",