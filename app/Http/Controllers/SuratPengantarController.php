<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratPengantar;
use Barryvdh\DomPDF\Facade\PDF;
use Carbon\Carbon;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

class SuratPengantarController extends Controller
{

    public function DownloadSuratPengantar($id)
    {
        $record = SuratPengantar::findOrFail($id);
        $templatePath = public_path('template/surat_pengantar.docx');
        if (!file_exists($templatePath)) {
            abort(404, 'Template tidak ditemukan');
        }

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

        // $penerima = $record->penerima ?? [];
        $penerima = (is_array($record->penerima) && count($record->penerima) > 0) ? $record->penerima[0] : [];
        // $nama = '';
        // $tgl = '';
        // $keteranganList = '';

        // Set single value fields
        $templateProcessor->setValues([
            'no_surat' => $record->no_surat,
            'tgl_surat'=> Carbon::parse($record->tgl_surat)->translatedFormat('d F Y'),
            'kepada' => strip_tags($record->kepada),
            'di' => $record->di,
            'tgl_diterima' => Carbon::parse($record->tgl_diterima)->translatedFormat('d F Y'),
            'penerima_nama' => $penerima['nama'] ?? '',
            'penerima_jabatan' => $penerima['jabatan'] ?? '',
            'penerima_pangkat_golongan' => $penerima['pangkat_golongan'] ?? '',
            'penerima_nip' => $penerima['nip'] ?? '',
            'nama2' => $record->pegawai->nama ?? '',
            'nip2' => $record->pegawai->nip ?? '',
            'jabatan2' => $record->pegawai->jabatan ?? '',
            'pangkat2' => $record->pegawai->pangkat ?? '',
            'golongan2' => $record->pegawai->golongan ?? '',
        ]);

        // Ambil detail_naskah (array)
        $details = $record->detail_naskah ?? [];

        // Clone row sesuai jumlah detail_naskah
        $templateProcessor->cloneRow('naskah_dinas', count($details));

        // Isi data ke setiap baris
        foreach ($details as $i => $detail) {
            $index = $i + 1;
            $templateProcessor->setValue("naskah_dinas#{$index}", $detail['naskah_dinas'] ?? '');
            $templateProcessor->setValue("jumlah#{$index}", $detail['jumlah'] ?? '');
            $templateProcessor->setValue("keterangan#{$index}", $detail['keterangan'] ?? '');
        }

        $filename = 'surat_pengantar_' . $record->id . '.docx';
        $savePath = storage_path('app/public/' . $filename);
        $templateProcessor->saveAs($savePath);

        return response()->download($savePath)->deleteFileAfterSend(true);
    }
    
}
