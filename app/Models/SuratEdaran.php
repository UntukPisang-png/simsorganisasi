<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratEdaran extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'kepada',
        'no_surat',
        'tahun_edaran',
        'isi_surat',
        'tgl_surat',
        'jabatan_ttd',
        'nama_ttd',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'tgl_surat' => 'date',
    ];

    public function suratKeluar()
    {
        return $this->belongsTo(SuratKeluar::class, 'surat_keluar_id');
    }

    protected static function booted()
    {
        static::deleting(function ($suratEdaran) {
            if ($suratEdaran->suratKeluar) {
                $suratEdaran->suratKeluar->delete();
            }
        });
    }
}
