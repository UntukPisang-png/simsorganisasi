<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BiayaPerjadin extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uang_harian',
        'penginapan',
        'transportasi',
        'total_perjadin',
        's_p_p_d_id',
        'pegawai_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'penginapan' => 'array',
        'transportasi' => 'array',
        'total_perjadin' => 'integer',
        's_p_p_d_id' => 'integer',
        'pegawai_id' => 'integer',
    ];

    public function sPPD(): BelongsTo
    {
        return $this->belongsTo(SPPD::class);
    }

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }

    protected static function booted()
    {
        static::saving(function ($model) {
        
        $tglBerangkat = $model->sPPD->tgl_berangkat ?? null;
        $tglKembali = $model->sPPD->tgl_kembali ?? null;
        $jumlahHari = 0;
        if ($tglBerangkat && $tglKembali) {
            $jumlahHari = \Carbon\Carbon::parse($tglBerangkat)->diffInDays(\Carbon\Carbon::parse($tglKembali)) + 1;
        }
        $totalHarian = $model->uang_harian * $jumlahHari;

        // Hitung total dari field 'jumlah' pada penginapan
        $penginapan = $model->penginapan ?? [];
        if (is_string($penginapan)) {
            $penginapan = json_decode($penginapan, true);
        }
        $total_penginapan = 0;
        if (is_array($penginapan)) {
            $total_penginapan = collect($penginapan)->sum(function ($item) {
                return (int) ($item['total_penginapan'] ?? 0);
            });
        }

        // Hitung total dari field 'jumlah' pada transportasi
        $transportasi = $model->transportasi ?? [];
        if (is_string($transportasi)) {
            $transportasi = json_decode($transportasi, true);
        }
        $total_transportasi = 0;
        if (is_array($transportasi)) {
            $total_transportasi = collect($transportasi)->sum(function ($item) {
                return (int) ($item['biaya'] ?? 0);
            });
        }

        // Jumlahkan total_penginapan dan total_transportasi ke total_perjadin
        $model->total_perjadin = $totalHarian + $total_penginapan + $total_transportasi;
    });
    }
}
