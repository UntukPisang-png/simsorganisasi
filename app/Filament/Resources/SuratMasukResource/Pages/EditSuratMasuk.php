<?php

namespace App\Filament\Resources\SuratMasukResource\Pages;

use App\Filament\Resources\SuratMasukResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Http;

class EditSuratMasuk extends EditRecord
{
    protected static string $resource = SuratMasukResource::class;

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
        $suratMasuk = $this->record;

        // Ambil pegawai dengan role kabag
        $pegawais = \App\Models\Pegawai::whereHas('user.roles', function($q) {
            $q->where('name', 'kabag');
        })->get();

        foreach ($pegawais as $pegawai) {
            $noHp = $pegawai->no_hp;
            $nama = $pegawai->nama;
            if ($noHp) {
                $tanggal = \Carbon\Carbon::parse($suratMasuk->tgl_diterima)->format('d F Y');
                Http::withHeaders([
                    'Authorization' => 'dAh23LZBvCzY3D9BQT5S',
                ])->asMultipart()->post('https://api.fonnte.com/send', [
                    [
                        'name' => 'target',
                        'contents' => $noHp,
                    ],
                    [
                        'name' => 'message',
                        'contents' => "Halo {$nama}, Ada perubahan pada Surat Masuk dari *{$suratMasuk->pengirim}* pada tanggal *{$tanggal}*, dengan perihal: *{$suratMasuk->perihal}*." . "\n" . "Mohon periksa kembali surat dengan nomor agenda *{$suratMasuk->id}* di website, Terima kasih.",
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
