<?php

namespace App\Filament\Resources\SuratKeluarResource\Pages;

use App\Filament\Resources\LaporanKegiatanResource\Pages\EditLaporanKegiatan;
use App\Filament\Resources\NotaDinasResource\Pages\EditNotaDinas;
use App\Filament\Resources\SPPDResource\Pages\EditSPPD;
use App\Filament\Resources\SuratDinasResource\Pages\EditSuratDinas;
use App\Filament\Resources\SuratEdaranResource\Pages\EditSuratEdaran;
use App\Filament\Resources\SuratKeluarResource;
use App\Filament\Resources\SuratPengantarResource\Pages\EditSuratPengantar;
use App\Filament\Resources\SuratTugasResource\Pages\CreateSuratTugas;
use App\Filament\Resources\SuratTugasResource\Pages\EditSuratTugas;
use App\Filament\Resources\SuratUndanganResource\Pages\EditSuratUndangan;
use App\Filament\Resources\TelaahanStafResource\Pages\EditTelaahanStaf;
use App\Models\SuratKeluar;
use App\Models\SuratTugas;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSuratKeluar extends EditRecord
{
    protected static string $resource = SuratKeluarResource::class;

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

public function mount($record): void
{
    $suratKeluar = SuratKeluar::findOrFail($record);

    if ($suratKeluar->kategori_id == 1 && $suratKeluar->suratUndangan()->exists()) {
        $suratUndangan = $suratKeluar->suratUndangan()->first();
        if ($suratUndangan) {
            $url = EditSuratUndangan::getUrl(['record' => $suratUndangan->id]);
            header("Location: $url");
            exit;
        }
    }
    if ($suratKeluar->kategori_id == 4 && $suratKeluar->notaDinas()->exists()) {
        $notaDinas = $suratKeluar->notaDinas()->first();
        if ($notaDinas) {
            $url = EditNotaDinas::getUrl(['record' => $notaDinas->id]);
            header("Location: $url");
            exit;
        }
    }
    if ($suratKeluar->kategori_id == 5 && $suratKeluar->suratDinas()->exists()) {
        $suratDinas = $suratKeluar->suratDinas()->first();
        if ($suratDinas) {
            $url = EditSuratDinas::getUrl(['record' => $suratDinas->id]);
            header("Location: $url");
            exit;
        }
    }
    if ($suratKeluar->kategori_id == 6 && $suratKeluar->suratEdaran()->exists()) {
        $suratEdaran = $suratKeluar->suratEdaran()->first();
        if ($suratEdaran) {
            $url = EditSuratEdaran::getUrl(['record' => $suratEdaran->id]);
            header("Location: $url");
            exit;
        }
    }
    if ($suratKeluar->kategori_id == 7 && $suratKeluar->suratPengantar()->exists()) {
        $suratPengantar = $suratKeluar->suratPengantar()->first();
        if ($suratPengantar) {
            $url = EditSuratPengantar::getUrl(['record' => $suratPengantar->id]);
            header("Location: $url");
            exit;
        }
    }
    if ($suratKeluar->kategori_id == 8 && $suratKeluar->suratTugas()->exists()) {
        $suratTugas = $suratKeluar->suratTugas()->first();
        if ($suratTugas) {
            $url = EditSuratTugas::getUrl(['record' => $suratTugas->id]);
            header("Location: $url");
            exit;
        }
    }
    if ($suratKeluar->kategori_id == 9 && $suratKeluar->telaahanStaf()->exists()) {
        $telaahanStaf = $suratKeluar->telaahanStaf()->first();
        if ($telaahanStaf) {
            $url = EditTelaahanStaf::getUrl(['record' => $telaahanStaf->id]);
            header("Location: $url");
            exit;
        }
    }
    // if ($suratKeluar->kategori_id == 10 && $suratKeluar->sPPD()->exists()) {
    //     $sPPD = $suratKeluar->sPPD()->first();
    //     if ($sPPD) {
    //         $url = EditSPPD::getUrl(['record' => $sPPD->id]);
    //         header("Location: $url");
    //         exit;
    //     }
    // }
    // if ($suratKeluar->kategori_id == 11 && $suratKeluar->laporanKegiatan()->exists()) {
    //     $laporanKegiatan = $suratKeluar->laporanKegiatan()->first();
    //     if ($laporanKegiatan) {
    //         $url = EditLaporanKegiatan::getUrl(['record' => $laporanKegiatan->id]);
    //         header("Location: $url");
    //         exit;
    //     }
    // }

    parent::mount($record);
}
}
