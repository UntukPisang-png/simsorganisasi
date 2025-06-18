<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratUndangan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'no_surat',
        'lampiran',
        'perihal',
        'tgl_surat',
        'kepada',
        'di',
        'isi_surat',
        'tgl_undangan',
        'tempat_undangan',
        'waktu_undangan',
        'penutup',
        'nama_ttd',
        'jabatan_ttd',
        'nip_ttd',
        // 'surat_keluar_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'tgl_surat' => 'date',
        'tgl_undangan' => 'date',
        // 'surat_keluar_id' => 'integer',
    ];

    public function suratKeluar()
    {
        return $this->belongsTo(SuratKeluar::class, 'surat_keluar_id');
    }

    protected static function booted()
    {
        static::deleting(function ($suratUndangan) {
            if ($suratUndangan->suratKeluar) {
                $suratUndangan->suratKeluar->delete();
            }
        });
    }
}
