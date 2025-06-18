<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SuratTugas extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'surat_masuk_id',
        'no_surat',
        'tgl_surat',
        'isi_surat',
        'penutup',
        'nama_ttd',
        'jabatan_ttd',
        'nip_ttd',
        // 'pegawai_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'tgl_surat' => 'date',
        // 'pegawai_id' => 'integer',
    ];

    public function suratMasuk()
    {
        return $this->belongsTo(SuratMasuk::class, 'surat_masuk_id');
    }
    public function suratKeluar()
    {
        return $this->belongsTo(SuratKeluar::class, 'surat_keluar_id');
    }
    public function pegawai(): BelongsToMany
    {
        return $this->belongsToMany(Pegawai::class, 'tugas_pegawais' );
    }
    public function SPPD()
    {
        return $this->hasMany(SPPD::class, 's_p_p_d_id');
    }

    protected static function booted()
    {
        static::deleting(function ($suratTugas) {
            if ($suratTugas->suratKeluar) {
                $suratTugas->suratKeluar->delete();
            }
        });
    }
}
