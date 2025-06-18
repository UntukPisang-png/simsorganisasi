<?php

namespace App\Filament\Resources\SuratMasukResource\Pages;

use App\Filament\Resources\SuratMasukResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Http;

class CreateSuratMasuk extends CreateRecord
{
    protected static string $resource = SuratMasukResource::class;
    protected static bool $canCreateAnother = false;

    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
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
                        'contents' => "Halo {$nama}, Ada Surat Masuk dari *{$suratMasuk->pengirim}* pada tanggal *{$tanggal}*, dengan perihal: *{$suratMasuk->perihal}*." . "\n" . "Mohon disposisi surat dengan nomor agenda *{$suratMasuk->id}* di website, Terima kasih.",
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
