<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratDinas extends Model
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
        'sifat',
        'lampiran',
        'perihal',
        'kepada',
        'tempat',
        'isi_surat',
        // 'surat_keluar_id',
        'jabatan_ttd',
        'nama_ttd',
        'nip_ttd',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'tgl_surat' => 'date',
        // 'surat_keluar_id' => 'integer',
    ];

    public function suratKeluar()
    {
        return $this->belongsTo(SuratKeluar::class, 'surat_keluar_id');
    }

    protected static function booted()
    {
        static::deleting(function ($suratDinas) {
            if ($suratDinas->suratKeluar) {
                $suratDinas->suratKeluar->delete();
            }
        });
    }
}
