<?php

namespace App\Filament\Resources\SPPDResource\Pages;

use App\Filament\Resources\SPPDResource;
use App\Models\SuratKeluar;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Http;

class CreateSPPD extends CreateRecord
{
    protected static string $resource = SPPDResource::class;
    protected static bool $canCreateAnother = false;

    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $sPPD = $this->record;
        $pegawai = $sPPD->pegawai; // Karena relasi BelongsTo, hanya ada satu pegawai terkait

        if ($pegawai && $pegawai->no_hp) {
            $noHp = $pegawai->no_hp; // Format harus sesuai, contoh: 628123456789
            $nama = $pegawai->nama;

            Http::withHeaders([
                'Authorization' => 'dAh23LZBvCzY3D9BQT5S', // Ganti dengan API Key Fonnte Anda
            ])->asMultipart()->post('https://api.fonnte.com/send', [
                [
                    'name' => 'target',
                    'contents' => $noHp,
                ],
                [
                    'name' => 'message',
                    'contents' => "Halo {$nama}, Anda mendapat Surat Perjalanan Dinas atas perintah dari *{$sPPD->perintah_dari}* dengan nomor *{$sPPD->nomor}*, tujuan *{$sPPD->tujuan}* selama *{$sPPD->lama_perjadin}*" . "\n\n" . "Untuk lebih detail, cek SPD anda di website. Terima kasih.",
                ],
                [
                    'name' => 'countryCode',
                    'contents' => '62',
                ],
            ]);
        }
    }

    // protected function afterCreate(): void
    // {
    //     $sPPD = $this->record;
    //     $pegawais = $sPPD->pegawai;

    //     foreach ($pegawais as $pegawai) {
    //         $noHp = $pegawai->no_hp; // pastikan format: 628123456789
    //         $nama = $pegawai->nama;
    //         if ($noHp) {
    //             Http::withHeaders([
    //                 'Authorization' => 'dAh23LZBvCzY3D9BQT5S', // Ganti dengan API Key Fonnte Anda
    //             ])->asMultipart()->post('https://api.fonnte.com/send', [
    //                 [
    //                     'name' => 'target',
    //                     'contents' => $noHp,
    //                 ],
    //                 [
    //                     'name' => 'message',
    //                     'contents' => "Halo {$nama}, Anda mendapat Surat Perjalanan Dinas atas perintah dari *{$sPPD->perintah_dari}* dengan nomor *{$sPPD->nomor}*, tujuan *{$sPPD->tujuan}* selama *{$sPPD->lama_perjadin}*" . "\n" . "\n" . "Untuk lebih detail, cek SPD anda di website, Terima kasih.",
    //                 ],
    //                 [
    //                     'name' => 'countryCode',
    //                     'contents' => '62',
    //                 ],
    //             ]);
    //         }
    //     }
    // }

    //kirim data ke tabel surat keluar
    // protected function afterCreate(): void
    // {
    //     $sPPD = $this->record;
    //     $suratKeluar = SuratKeluar::create([
    //         'no_suratkeluar' => $sPPD->nomor,
    //         'tgl_suratkeluar' => $sPPD->tgl_surat,
    //         // 'perihal' => $sPPD->maksud,
    //         'tujuan' => $sPPD->pegawai->nama,
    //         'kategori_id' => "10",
    //         'file_suratkeluar' => route('download.SPPD', $sPPD->id),
    //     ]);
    //     $sPPD->surat_keluar_id = $suratKeluar->id;
    //     $sPPD->save();
    // }
}
