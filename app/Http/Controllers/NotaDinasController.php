<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NotaDinas;
use Barryvdh\DomPDF\Facade\PDF;
use Carbon\Carbon;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

class NotaDinasController extends Controller
{

    public function DownloadNotaDinas($id)
    {
        $record = NotaDinas::findOrFail($id);
        $templatePath = public_path('template/nota_dinas.docx');
        if (!file_exists($templatePath)) {
            abort(404, 'Template tidak ditemukan');
        }

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);
        $templateProcessor->setValues([
            'kepada' => strip_tags($record->kepada),
            'dari' => strip_tags($record->dari),
            'tembusan' => strip_tags($record->tembusan),
            'no_surat' => $record->no_surat,
            'tgl_surat'=> Carbon::parse($record->tgl_surat)->translatedFormat('d F Y'),
            'sifat' => $record->sifat,
            'lampiran' => $record->lampiran,
            'perihal' => $record->perihal,
            'isi_surat' => strip_tags($record->isi_surat),
            'detail_surat' => strip_tags($record->detail_surat),
            'penutup' => strip_tags($record->penutup),
            'paraf' => strip_tags($record->paraf),
            'nama_ttd'=> $record->nama_ttd,
            'jabatan_ttd'=> $record->jabatan_ttd,
            'pangkat'=> $record->pangkat,
            'golongan'=> $record->golongan,
            'nip_ttd'=> $record->nip_ttd,
        ]);

        $filename = 'nota_dinas_' . $record->id . '.docx';
        $savePath = storage_path('app/public/' . $filename);
        $templateProcessor->saveAs($savePath);

        return response()->download($savePath)->deleteFileAfterSend(true);
    }
    
}
