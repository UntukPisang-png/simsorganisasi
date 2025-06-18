<?php

namespace App\Filament\Imports;

use App\Models\Pegawai;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class PegawaiImporter extends Importer
{
    protected static ?string $model = Pegawai::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nama')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('nip')
                ->requiredMapping()
                ->rules(['required','max:255']),
            ImportColumn::make('golongan')
                ->requiredMapping()
                ->rules(['required','max:255']),
            ImportColumn::make('pangkat')
                ->requiredMapping()
                ->rules(['required','max:255']),
            ImportColumn::make('jabatan')
                ->requiredMapping()
                ->rules(['required','max:255']),
            ImportColumn::make('no_hp')
                ->requiredMapping()
                ->rules(['required','max:255']),
            ImportColumn::make('user_id')
                ->rules(['max:255']),
        ];
    }

    public function resolveRecord(): ?Pegawai
    {
        // return User::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Pegawai();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Import data pegawai dengan total ' . number_format($import->successful_rows) . ' ' . str('baris')->plural($import->successful_rows) . ' berhasil.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' gagal import.';
        }

        return $body;
    }
}
