<?php

namespace App\Filament\Resources\SPPDResource\Pages;

use App\Filament\Resources\SPPDResource;
use App\Models\SuratKeluar;
use Blueprint\Builder;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Http;

class EditSPPD extends EditRecord
{
    protected static string $resource = SPPDResource::class;
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('suratTugas');
    }
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

    // protected function afterSave(): void
    // {
    //     $sPPD = $this->record;
    //     $suratKeluar = $sPPD->suratKeluar; 
    //     if ($suratKeluar){
    //         $suratKeluar->update([
    //             'no_suratkeluar' => $sPPD->nomor,
    //             'tgl_suratkeluar' => $sPPD->tgl_surat,
    //             'perihal' => $sPPD->maksud,
    //             'tujuan' => $sPPD->pegawai->nama,
    //             'kategori_id' => "10",
    //             'file_suratkeluar' => route('download.SPPD', $sPPD->id),
    //         ]);
    //     }
    // }

    protected function afterSave(): void
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
                        'contents' => "Halo {$nama}, terdapat perubahan pada Surat Perjalanan Dinas dengan nomor *{$sPPD->nomor}*, tujuan *{$sPPD->tujuan}* selama *{$sPPD->lama_perjadin}*" . "\n" . "\n" . "Untuk lebih detail, cek SPD anda di website, Terima kasih.",
                    ],
                    [
                        'name' => 'countryCode',
                        'contents' => '62',
                    ],
                ]);
        }
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (isset($data['surat_tugas_id'])) {
            $suratTugas = \App\Models\SuratTugas::find($data['surat_tugas_id']);

            if ($suratTugas) {
                $data['no_surat'] = $suratTugas->no_surat;
            }
        }

        return $data;
    }
}
