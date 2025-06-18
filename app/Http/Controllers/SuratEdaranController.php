<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratEdaran;
use Barryvdh\DomPDF\Facade\PDF;
use Carbon\Carbon;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

class SuratEdaranController extends Controller
{

    public function DownloadSuratEdaran($id)
    {
        $record = SuratEdaran::findOrFail($id);
        $templatePath = public_path('template/surat_edaran.docx');
        if (!file_exists($templatePath)) {
            abort(404, 'Template tidak ditemukan');
        }

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);
        $templateProcessor->setValues([
            'kepada' => strip_tags($record->kepada),
            'no_surat' => $record->no_surat,
            'tahun_edaran'=> $record->tahun_edaran,
            'isi_surat' => strip_tags($record->isi_surat),
            'tgl_surat'=> Carbon::parse($record->tgl_surat)->translatedFormat('d F Y'),
            'nama_ttd'=> $record->nama_ttd,
            'jabatan_ttd'=> $record->jabatan_ttd,
        ]);

        $filename = 'surat_edaran_' . $record->id . '.docx';
        $savePath = storage_path('app/public/' . $filename);
        $templateProcessor->saveAs($savePath);

        return response()->download($savePath)->deleteFileAfterSend(true);
    }
    
}
