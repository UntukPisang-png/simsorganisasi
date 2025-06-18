<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TelaahanStaf;
use Barryvdh\DomPDF\Facade\PDF;
use Carbon\Carbon;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

class TelaahanStafController extends Controller
{

    public function DownloadTelaahanStaf($id)
    {
        $record = TelaahanStaf::findOrFail($id);
        $templatePath = public_path('template/telaahan_staff.docx');
        if (!file_exists($templatePath)) {
            abort(404, 'Template tidak ditemukan');
        }

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);
        $templateProcessor->setValues([
            'kepada' => strip_tags($record->kepada),
            'dari' => strip_tags($record->dari),
            'tgl_surat' => Carbon::parse($record->tgl_surat)->translatedFormat('d F Y'),
            'no_telaahan' => $record->no_surat,
            'lampiran' => $record->lampiran,
            'perihal' => $record->perihal,
            'persoalan' => strip_tags($record->persoalan),
            'praanggapan' => strip_tags($record->praanggapan),
            'fakta' => strip_tags($record->fakta),
            'analisis' => strip_tags($record->analisis),
            'kesimpulan' => strip_tags($record->kesimpulan),
            'saran' => strip_tags($record->saran),
            'nama_ttd'=> $record->nama_ttd,
            'jabatan_ttd'=> $record->jabatan_ttd,
            'nip_ttd'=> $record->nip_ttd,
        ]);

        $filename = 'telaaahan_staf_' . $record->id . '.docx';
        $savePath = storage_path('app/public/' . $filename);
        $templateProcessor->saveAs($savePath);

        return response()->download($savePath)->deleteFileAfterSend(true);
    }
    
}
