<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaDinas extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'kepada',
        'dari',
        'tembusan',
        'tgl_surat',
        'no_surat',
        'sifat',
        'lampiran',
        'perihal',
        'isi_surat',
        'detail_surat',
        'penutup',
        'paraf',
        'jabatan_ttd',
        'nama_ttd',
        'pangkat',
        'golongan',
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
    ];

    public function suratKeluar()
    {
        return $this->belongsTo(SuratKeluar::class, 'surat_keluar_id');
    }

    protected static function booted()
    {
        static::deleting(function ($notaDinas) {
            if ($notaDinas->suratKeluar) {
                $notaDinas->suratKeluar->delete();
            }
        });
    }
}
