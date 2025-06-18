<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SPPD;
use Barryvdh\DomPDF\Facade\PDF;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

class SPPDController extends Controller
{

    public function DownloadSPPD($id)
    {
        $record = SPPD::findOrFail($id);
        $templatePath = public_path('template/sppd.docx');
        if (!file_exists($templatePath)) {
            abort(404, 'Template tidak ditemukan');
        }

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

        $details = $record->pengikut ?? [];
        $namaList = '';
        $tglLahirList = '';
        $keteranganList = '';

        $templateProcessor->setValue('pengikut', count($details));
        foreach ($details as $i => $detail) {
            $index = $i + 1;
            $namaList .= "{$index}. {$detail['nama']}\n";
            //Jika ingin tgl lahir dan keterangan tampil dengan numbering
            // $tglLahirList .= "{$index}. " . ($detail['tgl_lahir'] ? \Carbon\Carbon::parse($detail['tgl_lahir'])->translatedFormat('d F Y') : '') . "\n";
            // $keteranganList .= "{$index}. " . strip_tags($detail['keterangan']) . "\n";
            
            //tanpa numbering
            $tglLahirList .= ($detail['tgl_lahir'] ? \Carbon\Carbon::parse($detail['tgl_lahir'])->translatedFormat('d F Y') : '') . "\n";
            $keteranganList .= $detail['keterangan'] . "\n";
        }

        $templateProcessor->setValues([
            'nomor' => $record->suratTugas->no_surat,
            'perintah_dari' => strip_tags($record->perintah_dari),
            'nama' => $record->pegawai->nama,
            'nip' => $record->pegawai->nip,
            'pangkat' => $record->pegawai->pangkat,
            'golongan' => $record->pegawai->golongan,
            'jabatan' => $record->pegawai->jabatan,
            'instansi' => 'Bagian Organisasi Sekretariat Daerah Pemerintah Kota Banjarmasin',
            'maksud' => strip_tags($record->maksud),
            'berangkat' => $record->berangkat,
            'tujuan' => $record->tujuan,
            'angkutan' => $record->angkutan,
            'lama_perjadin' => $record->lama_perjadin,
            'tgl_berangkat' => $record->tgl_berangkat ? $record->tgl_berangkat->translatedFormat('d F Y') : '',
            'tgl_kembali' => $record->tgl_kembali ? $record->tgl_kembali->translatedFormat('d F Y') : '',
            'pengikut_nama' => trim($namaList),
            'pengikut_tgl_lahir' => trim($tglLahirList),
            'pengikut_keterangan' => trim($keteranganList),
            'tgl_lahir' => $record->tgl_lahir ? $record->tgl_lahir->translatedFormat('d F Y') : '',
            'keterangan_pengikut' => strip_tags($record->keterangan_pengikut),
            'bebasbiaya_instansi' => $record->bebasbiaya_instansi,
            'bebasbiaya_akun' => $record->bebasbiaya_akun,
            'keterangan' => strip_tags($record->keterangan),
            'tgl_surat' => $record->tgl_surat ? $record->tgl_surat->translatedFormat('d F Y') : '',
            'catatan_lembar2' => strip_tags($record->catatan_lembar2),
            'nama_ttd'=> $record->nama_ttd,
            'jabatan_ttd'=> $record->jabatan_ttd,
            'pangkat_ttd' => $record->pangkat_ttd,
            'nip_ttd'=> $record->nip_ttd,
        ]);

        $filename = 'sppd_' . $record->id . '.docx';
        $savePath = storage_path('app/public/' . $filename);
        $templateProcessor->saveAs($savePath);

        return response()->download($savePath)->deleteFileAfterSend(true);
    }
    
}
