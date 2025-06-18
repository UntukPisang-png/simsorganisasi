<?php

namespace App\Http\Controllers;

use App\Models\BiayaPerjadin;
use Illuminate\Http\Request;
use App\Models\SPPD;
use Barryvdh\DomPDF\Facade\PDF;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

class BiayaPerjadinController extends Controller
{

    private function terbilang($number)
    {
        $number = abs($number);
        $words = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        $temp = "";
        if ($number < 12) {
            $temp = $words[$number];
        } else if ($number < 20) {
            $temp = $this->terbilang($number - 10) . " belas";
        } else if ($number < 100) {
            $temp = $this->terbilang(floor($number / 10)) . " puluh " . $this->terbilang($number % 10);
        } else if ($number < 200) {
            $temp = "seratus " . $this->terbilang($number - 100);
        } else if ($number < 1000) {
            $temp = $this->terbilang(floor($number / 100)) . " ratus " . $this->terbilang($number % 100);
        } else if ($number < 2000) {
            $temp = "seribu " . $this->terbilang($number - 1000);
        } else if ($number < 1000000) {
            $temp = $this->terbilang(floor($number / 1000)) . " ribu " . $this->terbilang($number % 1000);
        } else if ($number < 1000000000) {
            $temp = $this->terbilang(floor($number / 1000000)) . " juta " . $this->terbilang($number % 1000000);
        } else if ($number < 1000000000000) {
            $temp = $this->terbilang(floor($number / 1000000000)) . " milyar " . $this->terbilang(fmod($number, 1000000000));
        } else if ($number < 1000000000000000) {
            $temp = $this->terbilang(floor($number / 1000000000000)) . " triliun " . $this->terbilang(fmod($number, 1000000000000));
        }
        return trim(preg_replace('/\s+/', ' ', $temp));
    }

    public function DownloadRincianBiaya($id)
    {
        $record = BiayaPerjadin::findOrFail($id);
        $templatePath = public_path('template/rincian_perjadin.docx');
        if (!file_exists($templatePath)) {
            abort(404, 'Template tidak ditemukan');
            // Fungsi untuk mengubah angka menjadi terbilang (dalam bahasa Indonesia)
            
        }

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

        //CloneRow Transportasi
        $details = $record->transportasi ?? [];
        $templateProcessor->cloneRow('berangkat_tujuan', count($details));
        // Isi data ke setiap baris
        foreach ($details as $i => $detail) {
            $index = $i + 1;
            $templateProcessor->setValue("berangkat_tujuan#{$index}", $detail['berangkat_tujuan'] ?? '');
            $templateProcessor->setValue("biaya#{$index}", isset($detail['biaya']) ? 'Rp ' . number_format($detail['biaya'], 0, ',', '.') : '');
        }

        //CloneRow Penginapan
        $detailsPenginapan = $record->penginapan ?? [];
        $templateProcessor->cloneRow('jenis_penginapan', count($detailsPenginapan));

        foreach ($detailsPenginapan as $i => $detail2) {
            $index = $i + 1;
            $jenis = $detail2['jenis_penginapan'] ?? '-';
            $lama = $detail2['lamanya'] ?? '-';
            $satuan = $detail2['satuan'] ?? '-';
            $biaya = isset($detail2['biaya']) ? 'Rp ' . number_format($detail2['biaya'], 0, ',', '.') : '-';
            $total = isset($detail2['total_penginapan']) ? 'Rp ' . number_format($detail2['total_penginapan'], 0, ',', '.') : '-';

            // Gabungkan lamanya, satuan, dan biaya dalam satu kolom
            $lamaSatuanBiaya = "{$lama} {$satuan} x {$biaya}";

            $templateProcessor->setValue("jenis_penginapan#{$index}", $jenis);
            $templateProcessor->setValue("lama_satuan_biaya#{$index}", $lamaSatuanBiaya);
            $templateProcessor->setValue("total_penginapan#{$index}", $total);
        }
        
        // Hitung jumlah hari perjalanan dinas
        $tglBerangkat = $record->sPPD->tgl_berangkat ?? null;
        $tglKembali = $record->sPPD->tgl_kembali ?? null;
        $jumlahHari = 0;
        if ($tglBerangkat && $tglKembali) {
            $jumlahHari = \Carbon\Carbon::parse($tglBerangkat)->diffInDays(\Carbon\Carbon::parse($tglKembali)) + 1;
        }
        $totalHarian = $record->uang_harian * $jumlahHari;
        // $totalTransport = collect($record->transportasi)->sum('biaya');
        // $totalPenginapan = collect($record->penginapan)->sum('total_penginapan');
        // $total = $totalHarian + $totalTransport + $totalPenginapan; //hitung total otomatis

        $templateProcessor->setValues([
            'nomor' => $record->sPPD->nomor ?? '',
            'tgl_surat' => $record->sPPD->tgl_surat ? \Carbon\Carbon::parse($record->sPPD->tgl_surat)->translatedFormat('d F Y') : '',
            'jumlah_hari' => $jumlahHari,
            'uang_harian' => $record->uang_harian ? 'Rp ' . number_format($record->uang_harian, 0, ',', '.') : '',
            'total_harian' => $totalHarian ? 'Rp ' . number_format($totalHarian, 0, ',', '.') : '',
            'total' => $record->total_perjadin ? 'Rp ' . number_format($record->total_perjadin, 0, ',', '.') : '',
            'total_kalimat' => $this->terbilang($record->total_perjadin),
        ]);

        $filename = 'rincian_perjadin_' . $record->id . '.docx';
        $savePath = storage_path('app/public/' . $filename);
        $templateProcessor->saveAs($savePath);

        return response()->download($savePath)->deleteFileAfterSend(true);
    }
    
}
