<?php

namespace App\Filament\Exports;

use App\Models\SuratMasuk;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class SuratMasukExporter extends Exporter
{
    protected static ?string $model = SuratMasuk::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('no_suratmasuk'),
            ExportColumn::make('tgl_suratmasuk'),
            ExportColumn::make('tgl_diterima'),
            ExportColumn::make('pengirim'),
            ExportColumn::make('perihal'),
            ExportColumn::make('file_suratmasuk'),
            ExportColumn::make('status_disposisi'),
            ExportColumn::make('kategori.nama_kategori'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Data Surat Masuk anda sebanyak ' . number_format($export->successful_rows) . ' ' . str('baris')->plural($export->successful_rows) . ' berhasil di ekspor.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
