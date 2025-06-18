<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratTugas;
use Barryvdh\DomPDF\Facade\PDF;
use Carbon\Carbon;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

class SuratTugasController extends Controller
{
    public function DownloadSuratTugas($id)
    {
        $record = SuratTugas::with('pegawai')->findOrFail($id);
        $templatePath = public_path('template/surat_tugas.docx');
        if (!file_exists($templatePath)) {
            abort(404, 'Template tidak ditemukan');
        }

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

        // Data pegawai
        $pegawaiList = $record->pegawai;
        $pegawaiCount = $pegawaiList->count();

        // Clone baris tabel sesuai jumlah pegawai
        $templateProcessor->cloneRow('nama_pegawai', $pegawaiCount);

        foreach ($pegawaiList as $i => $pegawai) {
            $row = $i + 1;
            $templateProcessor->setValue("nama_pegawai#{$row}", $pegawai->nama);
            $templateProcessor->setValue("pangkat_golongan#{$row}", $pegawai->pangkat . '/' . $pegawai->golongan);
            $templateProcessor->setValue("nip#{$row}", $pegawai->nip);
            $templateProcessor->setValue("jabatan#{$row}", $pegawai->jabatan);
        }

        // Set value lain seperti biasa
        $templateProcessor->setValues([
            'no_surat' => $record->no_surat,
            'tgl_surat'=> Carbon::parse($record->tgl_surat)->translatedFormat('d F Y'),
            'isi_surat' => strip_tags($record->isi_surat),
            'penutup' => strip_tags($record->penutup),
            'nama_ttd'=> $record->nama_ttd,
            'jabatan_ttd'=> $record->jabatan_ttd,
            'nip_ttd'=> $record->nip_ttd,
        ]);

        $filename = 'surat_tugas_' . $record->id . '.docx';
        $savePath = storage_path('app/public/' . $filename);
        $templateProcessor->saveAs($savePath);

        return response()->download($savePath)->deleteFileAfterSend(true);
    }
    
}
