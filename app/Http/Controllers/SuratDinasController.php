<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratDinas;
use Barryvdh\DomPDF\Facade\PDF;
use Carbon\Carbon;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

class SuratDinasController extends Controller
{

    public function DownloadSuratDinas($id)
    {
        $record = SuratDinas::findOrFail($id);
        $templatePath = public_path('template/surat_dinas.docx');
        if (!file_exists($templatePath)) {
            abort(404, 'Template tidak ditemukan');
        }

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);
        $templateProcessor->setValues([
            'no_surat' => $record->no_surat,
            'tgl_surat'=> Carbon::parse($record->tgl_surat)->translatedFormat('d F Y'),
            'sifat' => $record->sifat,
            'lampiran' => $record->lampiran,
            'perihal' => $record->perihal,
            'kepada' => $record->kepada,
            'tempat' => $record->tempat,
            'isi_surat' => strip_tags($record->isi_surat),
            'nama_ttd'=> $record->nama_ttd,
            'jabatan_ttd'=> $record->jabatan_ttd,
            'nip_ttd'=> $record->nip_ttd,
        ]);

        $filename = 'surat_dinas_' . $record->id . '.docx';
        $savePath = storage_path('app/public/' . $filename);
        $templateProcessor->saveAs($savePath);

        return response()->download($savePath)->deleteFileAfterSend(true);
    }
    
}
