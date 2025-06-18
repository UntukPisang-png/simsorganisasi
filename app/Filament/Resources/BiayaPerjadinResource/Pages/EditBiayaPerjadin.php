<?php

namespace App\Filament\Resources\BiayaPerjadinResource\Pages;

use App\Filament\Resources\BiayaPerjadinResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBiayaPerjadin extends EditRecord
{
    protected static string $resource = BiayaPerjadinResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }

    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (isset($data['s_p_p_d_id'])) {
            $SPPD = \App\Models\SPPD::find($data['s_p_p_d_id']);

            if ($SPPD) {
                // Set default values only if not already set (to avoid overwriting on edit)
                $data['nomor'] = $data['nomor'] ?? $SPPD->nomor;
                // $data['pegawai_id'] = $data['pegawai_id'] ?? $SPPD->pegawai_id;
                $data['nama_pegawai'] = $data['nama_pegawai'] ?? ($SPPD->pegawai?->nama ?? null);
                $data['lama_perjadin'] = $data['lama_perjadin'] ?? $SPPD->lama_perjadin;
            }
        }

        return $data;
    }
}
