<?php

namespace App\Http\Controllers;

use App\Models\BiayaPerjadin;
use App\Models\BiayaRiil;
use Illuminate\Http\Request;
use App\Models\SPPD;
use Barryvdh\DomPDF\Facade\PDF;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

class BiayaRiilController extends Controller
{

    public function DownloadBiayaRiil($id)
    {
        $record = BiayaRiil::findOrFail($id);
        $templatePath = public_path('template/biaya_riil.docx');
        if (!file_exists($templatePath)) {
            abort(404, 'Template tidak ditemukan');
            // Fungsi untuk mengubah angka menjadi terbilang (dalam bahasa Indonesia)
            
        }

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

        // Ambil pengeluaran (array)
        $details = $record->pengeluaran ?? [];

        // Clone row sesuai jumlah nama_pengeluaran
        $templateProcessor->cloneRow('nama_pengeluaran', count($details));

        // Isi data ke setiap baris
        foreach ($details as $i => $detail) {
            $index = $i + 1;
            $templateProcessor->setValue("nama_pengeluaran#{$index}", $detail['nama_pengeluaran'] ?? '');
            $templateProcessor->setValue("jumlah#{$index}", 'Rp ' . number_format((int)($detail['jumlah'] ?? 0), 0, ',', '.'));
            $templateProcessor->setValue("keterangan#{$index}", $detail['keterangan'] ?? '');
        }

        $templateProcessor->setValues([
            'nomor' => $record->SPPD->nomor ?? '' . ' ',
            'nama' => $record->SPPD->pegawai->nama ?? '',
            'nip' => $record->SPPD->pegawai->nip ?? '',
            'jabatan' => $record->SPPD->pegawai->jabatan ?? '',
            'tgl_surat' => $record->SPPD->tgl_surat ? \Carbon\Carbon::parse($record->SPPD->tgl_surat)->translatedFormat('d F Y') : '',
            'total' => $record->total_pengeluaran ? 'Rp ' . number_format($record->total_pengeluaran, 0, ',', '.') : '',
        ]);

        $filename = 'biaya_riil_' . $record->id . '.docx';
        $savePath = storage_path('app/public/' . $filename);
        $templateProcessor->saveAs($savePath);

        return response()->download($savePath)->deleteFileAfterSend(true);
    }
    
}
