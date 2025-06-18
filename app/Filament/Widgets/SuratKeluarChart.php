<?php

namespace App\Filament\Widgets;

use App\Models\Kategori;
use App\Models\SuratKeluar;
use Filament\Widgets\ChartWidget;

class SuratKeluarChart extends ChartWidget
{
    protected static ?string $heading = 'Surat Keluar Berdasarkan Kategori';
    protected int | string | array $columnSpan = 1;
    protected static ?string $maxHeight = '265px';
    protected function getData(): array
    {
        $data = SuratKeluar::selectRaw('kategori_id, COUNT(*) as total')
        ->groupBy('kategori_id')
        ->pluck('total', 'kategori_id');

        $total = $data->sum();

        // Ambil semua kategori sekaligus
        $kategoriNames = Kategori::whereIn('id', $data->keys())->pluck('nama_kategori', 'id');

        $labels = [];
        foreach ($data as $kategoriId => $jumlah) {
            $nama = $kategoriNames[$kategoriId] ?? 'Tidak diketahui';
            $persen = $total > 0 ? round(($jumlah / $total) * 100, 1) : 0;
            $labels[] = "{$nama}";
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Surat Keluar',
                    'data' => $data->values(),
                    'backgroundColor' => [
                        '#f59e42', '#3b82f6', '#10b981', '#ef4444', '#6366f1', '#fbbf24'
                    ],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    // protected int | string | array $columnSpan = [
    //     'md' => 1,
    //     'xl' => 1,
    // ];


}
