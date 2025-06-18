<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaporanKegiatan;
use Barryvdh\DomPDF\Facade\PDF;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

class LaporanKegiatanController extends Controller
{

    public function DownloadLaporanKegiatan($id)
    {
        $record = LaporanKegiatan::findOrFail($id);
        $templatePath = public_path('template/laporan_kegiatan.docx');
        if (!file_exists($templatePath)) {
            abort(404, 'Template tidak ditemukan');
        }

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);
        $templateProcessor->setValues([
            'no_laporan' => $record->no_laporan,
            'umum' => strip_tags($record->umum),
            'landasan' => strip_tags($record->landasan),
            'maksud' => strip_tags($record->maksud),
            'kegiatan' => strip_tags($record->kegiatan),
            'hasil' => strip_tags($record->hasil),
            'kesimpulan' => strip_tags($record->kesimpulan),
            'penutup' => strip_tags($record->penutup),
            'paraf' => strip_tags($record->paraf),
            'nama_ttd'=> $record->nama_ttd,
            'jabatan_ttd'=> $record->jabatan_ttd,
            'nip_ttd'=> $record->nip_ttd,
        ]);

        $filename = 'laporan_kegiatan_' . $record->id . '.docx';
        $savePath = storage_path('app/public/' . $filename);
        $templateProcessor->saveAs($savePath);

        return response()->download($savePath)->deleteFileAfterSend(true);
    }
    
}
