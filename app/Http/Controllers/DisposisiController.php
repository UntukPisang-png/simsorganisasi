<?php

namespace App\Http\Controllers;

use App\Models\Disposisi;
use Illuminate\Http\Request;
use App\Models\SuratDinas;
use Barryvdh\DomPDF\Facade\PDF;
use Carbon\Carbon;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

class DisposisiController extends Controller
{

    public function DownloadDisposisi($id)
    {
        $record = Disposisi::findOrFail($id);
        $templatePath = public_path('template/disposisi.docx');
        if (!file_exists($templatePath)) {
            abort(404, 'Template tidak ditemukan');
        }

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);
        $templateProcessor->setValues([
            'no_suratmasuk' => $record->suratMasuk->no_suratmasuk,
            'tgl_suratmasuk'=> Carbon::parse($record->suratMasuk->tgl_suratmasuk)->translatedFormat('d F Y'),
            'pengirim' => $record->suratMasuk->pengirim,
            'tgl_diterima' => Carbon::parse($record->suratMasuk->tgl_diterima)->translatedFormat('d F Y'),
            'no_agenda' => $record->surat_masuk_id,
            'sifat' => $record->sifat,
            'perihal' => $record->suratMasuk->perihal,
            'pegawai' => $record->pegawai->pluck('nama')->implode(', '),
            'tindakan' => $record->tindakan,
            'catatan' => $record->catatan,
        ]);

        $filename = 'disposisi_' . $record->id . '.docx';
        $savePath = storage_path('app/public/' . $filename);
        $templateProcessor->saveAs($savePath);

        return response()->download($savePath)->deleteFileAfterSend(true);
    }
    
}
