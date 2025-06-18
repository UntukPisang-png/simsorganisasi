<?php
namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\SuratMasuk;

class SuratMasukChart extends ChartWidget
{
    protected static ?string $heading = 'Jumlah Surat Masuk per Bulan';
    protected static ?int $sort = 1;
    protected function getData(): array
    {
        $data = SuratMasuk::selectRaw('MONTH(tgl_diterima) as bulan, COUNT(*) as total')
            ->whereYear('tgl_diterima', now()->year)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan');

        $labels = [];
        $values = [];
        for ($i = 1; $i <= 12; $i++) {
            $labels[] = \Carbon\Carbon::create()->month($i)->format('F');
            $values[] = $data[$i] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Surat Masuk',
                    'data' => $values,
                    'backgroundColor' => '#f59e42',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}