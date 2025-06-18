<?php

use App\Filament\Resources\DisposisiResource;
use App\Filament\Resources\DisposisiResource\Pages\CreateDisposisi;
use App\Filament\Resources\SuratKeluarResource\Pages\EditSuratKeluar;
use App\Filament\Resources\SuratTugasResource\Pages\EditSuratTugas;
use App\Http\Controllers\BiayaPerjadinController;
use App\Http\Controllers\BiayaRiilController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DisposisiController;
use App\Http\Controllers\LaporanKegiatanController;
use App\Http\Controllers\NotaDinasController;
use App\Http\Controllers\SPPDController;
use App\Http\Controllers\SuratUndanganController;
use App\Http\Controllers\SuratDinasController;
use App\Http\Controllers\SuratEdaranController;
use App\Http\Controllers\SuratPengantarController;
use App\Http\Controllers\SuratTugasController;
use App\Http\Controllers\TelaahanStafController;

// Route::get('fialment/resources/disposisi/create', [CreateDisposisi::class, 'create'])->name('disposisi.create');
// Route::get('/surat/edit/{record}', [EditSuratKeluar::class, 'mount'])->name('edit.surat');


Route::get('/download.suratundangan/{id}', [SuratUndanganController::class, 'DownloadSuratUndangan'])->name('download.suratundangan');
Route::get('/surattugas/download/{id}', [SuratTugasController::class, 'DownloadSuratTugas'])->name('download.surattugas');
Route::get('/suratdinas/download/{id}', [SuratDinasController::class, 'DownloadSuratDinas'])->name('download.suratdinas');
Route::get('/notadinas/download/{id}', [NotaDinasController::class, 'DownloadNotaDinas'])->name('download.notadinas');
Route::get('/suratedaran/download/{id}', [SuratEdaranController::class, 'DownloadSuratEdaran'])->name('download.suratedaran');
Route::get('/suratpengantar/download/{id}', [SuratPengantarController::class, 'DownloadSuratPengantar'])->name('download.suratpengantar');
Route::get('/telaahanstaf/download/{id}', [TelaahanStafController::class, 'DownloadTelaahanStaf'])->name('download.telaahanstaf');
Route::get('/laporankegiatan/download/{id}', [LaporanKegiatanController::class, 'DownloadLaporanKegiatan'])->name('download.laporankegiatan');
Route::get('/sppd/download/{id}', [SPPDController::class, 'DownloadSPPD'])->name('download.SPPD');
Route::get('/biayaperjadin/download/{id}', [BiayaPerjadinController::class, 'DownloadRincianBiaya'])->name('download.rincianbiaya');
Route::get('/biayariil/download/{id}', [BiayaRiilController::class, 'DownloadBiayaRiil'])->name('download.biayariil');
Route::get('/disposisi/download/{id}', [DisposisiController::class, 'DownloadDisposisi'])->name('download.disposisi');
// Route::get('/', function () {
//     return view('welcome');
// });