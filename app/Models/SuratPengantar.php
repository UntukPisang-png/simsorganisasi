<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratPengantar extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'no_surat',
        'tgl_surat',
        'kepada',
        'di',
        'detail_naskah',
        'tgl_diterima',
        'penerima',
        'pegawai_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'tgl_surat' => 'date',
        'tgl_diterima' => 'date',
        'detail_naskah' => 'array',
        'penerima' => 'array',
        'pegawai_id' => 'integer',
    ];

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function suratKeluar()
    {
        return $this->belongsTo(SuratKeluar::class, 'surat_keluar_id');
    }

    protected static function booted()
    {
        static::deleting(function ($suratPengantar) {
            if ($suratPengantar->suratKeluar) {
                $suratPengantar->suratKeluar->delete();
            }
        });
    }
}
