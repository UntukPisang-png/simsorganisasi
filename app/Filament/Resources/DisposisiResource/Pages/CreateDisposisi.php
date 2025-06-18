<?php

namespace App\Filament\Resources\DisposisiResource\Pages;

use App\Filament\Resources\DisposisiResource;
use App\Models\Disposisi;
use App\Models\Pegawai;
use App\Models\SuratMasuk;
use App\Notifications\DisposisiNotification;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Enums\StatusDisposisi;
use Illuminate\Support\Facades\Http;

class CreateDisposisi extends CreateRecord
{
    protected static string $resource = DisposisiResource::class;
    protected static bool $canCreateAnother = false;
    protected static ?string $modelLabel = 'Disposisi';
    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $disposisi = $this->record;
        $pegawais = $disposisi->pegawai;

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
                        'contents' => "Halo {$nama}, Anda menerima disposisi surat dengan nomor *{$disposisi->suratMasuk->no_suratmasuk}* dari Kepala Bagian Organisasi dengan perihal: *{$disposisi->suratMasuk->perihal}*." . "\n" . "Catatan disposisi: *{$disposisi->catatan}*." ."\n"."\n". "Mohon cek disposisi anda di website, Terima kasih.",
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
