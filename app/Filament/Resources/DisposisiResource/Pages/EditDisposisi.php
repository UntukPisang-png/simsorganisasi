<?php

namespace App\Filament\Resources\DisposisiResource\Pages;

use App\Filament\Resources\DisposisiResource;
use App\Models\SuratMasuk;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class EditDisposisi extends EditRecord
{
    protected static string $resource = DisposisiResource::class;
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('suratMasuk');
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

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (isset($data['surat_masuk_id'])) {
            $suratMasuk = \App\Models\SuratMasuk::find($data['surat_masuk_id']);

            if ($suratMasuk) {
                $data['no_suratmasuk'] = $suratMasuk->no_suratmasuk;
                $data['tgl_suratmasuk'] = $suratMasuk->tgl_suratmasuk->format('d-m-Y');
                $data['pengirim'] = $suratMasuk->pengirim;
                $data['perihal'] = $suratMasuk->perihal;
            }
        }

        return $data;
    }

        protected function afterSave(): void
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
                        'contents' => "Halo {$nama}, Ada perubahan pada disposisi surat masuk dari Kepala Bagian Organisasi dengan nomor surat : *{$disposisi->suratMasuk->no_suratmasuk}*, perihal: *{$disposisi->suratMasuk->perihal}*." ."\n"."\n". "Mohon cek kembali disposisi anda di website, Terima kasih.",
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