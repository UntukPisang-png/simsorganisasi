<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratUndangan;
use Barryvdh\DomPDF\Facade\PDF;
use Carbon\Carbon;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

class SuratUndanganController extends Controller
{
    public function DownloadSuratUndangan($id)
    {
        //tutor yt berhasil (PDF)
        // $suratUndangan = SuratUndangan::where ('id', $id)->first();
        // $pdf = Pdf::loadView('pdf.suratundangan', compact('suratUndangan'))->
        //         setOption([
        //     'tempDir' => public_path(),
        //     'chroot' => public_path(),
        // ]);
        // return $pdf->download('surat-undangan.pdf');

        $record = SuratUndangan::findOrFail($id);
        $templatePath = public_path('template/surat_undangan.docx');
        if (!file_exists($templatePath)) {
            abort(404, 'Template tidak ditemukan');
        }

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);
        $templateProcessor->setValues([
            'no_surat' => $record->no_surat,
            'tgl_surat'=> Carbon::parse($record->tgl_surat)->translatedFormat('d F Y'),
            'lampiran'=> $record->lampiran,
            'perihal' => $record->perihal,
            'kepada' => $record->kepada,
            'di' => $record->di,
            'isi_surat' => strip_tags($record->isi_surat),
            'tgl_undangan' => Carbon::parse($record->tgl_surat)->translatedFormat('l, d F Y'),
            'waktu_undangan' => $record->waktu_undangan,
            'tempat_undangan' => $record->tempat_undangan,
            'penutup' => strip_tags($record->penutup),
            'nama_ttd'=> strip_tags($record->nama_ttd),
            'jabatan_ttd'=> strip_tags($record->jabatan_ttd),
            'nip_ttd'=> strip_tags($record->nip_ttd),
        ]);

        $filename = 'surat_undangan_' . $record->id . '.docx';
        $savePath = storage_path('app/public/' . $filename);
        $templateProcessor->saveAs($savePath);

        return response()->download($savePath)->deleteFileAfterSend(true);

    }
    
}
