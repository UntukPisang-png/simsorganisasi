<?php

namespace App\Filament\Pages;

use App\Models\Disposisi;
use App\Models\SuratMasuk;
use Filament\Pages\Page;

class DisposisiSuratMasuk extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.disposisi-surat-masuk';

    protected static bool $shouldRegisterNavigation = false;

    public ?SuratMasuk $record = null;
    public ?string $suratmasukId = null;

    public function mount ()
    {
        $this->suratmasukId = request()->query('suratMasuk_id');
    }
}
