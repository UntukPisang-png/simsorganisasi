<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BiayaRiil extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pengeluaran',
        'total_pengeluaran',
        's_p_p_d_id',
        'biaya_perjadin_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'pengeluaran' => 'array',
        's_p_p_d_id' => 'integer',
        'biaya_perjadin_id' => 'integer',
    ];

    public function sPPD(): BelongsTo
    {
        return $this->belongsTo(SPPD::class);
    }

    public function biayaPerjadin(): BelongsTo
    {
        return $this->belongsTo(BiayaPerjadin::class);
    }

    protected static function booted()
    {
        static::saving(function ($model) {
            $pengeluaran = $model->pengeluaran ?? [];
            if (is_string($pengeluaran)) {
                $pengeluaran = json_decode($pengeluaran, true);
            }
            if (!is_array($pengeluaran)) {
                $model->total_pengeluaran = 0;
            } else {
                $model->total_pengeluaran = collect($pengeluaran)->sum(function ($item) {
                    return (int) ($item['jumlah'] ?? 0);
                });
            }
        });
    }
}
