<?php

namespace App\Filament\Widgets;

use App\Models\Pegawai;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use App\Models\SPPD;
use App\Models\SuratTugas;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatOverview extends BaseWidget
{
    protected static ?string $maxHeight = '60px';
    protected function getStats(): array
    {
        $totalSuratMasuk = SuratMasuk::count();
        $totalDisposisi = SuratMasuk::where('status_disposisi', 'Sudah Disposisi')->count();
        $persenDisposisi = $totalSuratMasuk > 0 ? round(($totalDisposisi / $totalSuratMasuk) * 100, 2) : 0;

        return [
            Stat::make('Jumlah Disposisi', "{$totalDisposisi} / {$totalSuratMasuk} Surat Masuk")
                ->description("{$persenDisposisi}% sudah didisposisi")
                ->icon('heroicon-o-document-text')
                ->color('success'),
            Stat::make('Total Surat Keluar', 'Total Surat Keluar')
                ->value(SuratKeluar::count()) 
                ->icon('heroicon-o-inbox')
                ->color('secondary'), 
            // Stat::make('Pegawai yang Melaksanakan Tugas', Pegawai::whereHas('suratTugas', function ($q) {
            //     $q->where('status_bertugas', 'Melaksanakan Tugas');
            // })->distinct()->count() . ' dari ' . Pegawai::count() . ' Pegawai')
            // // ->description('Pegawai yang sedang melaksanakan tugas')
            // ->icon('heroicon-o-users')
            // ->color('primary'),
            Stat::make(
                'Pegawai Melaksanakan Perjalanan Dinas',
                Pegawai::whereHas('sPPD', function ($q) {
                    $q->where('status_perjalanan', 'Dalam Perjalanan');
                })->distinct()->count() . ' dari ' . Pegawai::count() . ' Pegawai'
            )
            ->icon('heroicon-o-briefcase')
            ->color('info'),
        ];
    }
}
