<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanKegiatan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'no_laporan',
        'umum',
        'landasan',
        'maksud',
        'kegiatan',
        'hasil',
        'kesimpulan',
        'penutup',
        'paraf',
        'pegawai_id',
        'jabatan_ttd',
        'nama_ttd',
        'nip_ttd',
        'surat_tugas_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'pegawai_id' => 'integer',
        'surat_tugas_id' => 'integer',
    ];

    public function suratTugas()
    {
        return $this->belongsTo(SuratTugas::class);
    }
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }
    
    // public function suratKeluar()
    // {
    //     return $this->belongsTo(SuratKeluar::class, 'surat_keluar_id');
    // }

    // protected static function booted()
    // {
    //     static::deleting(function ($laporanKegiatan) {
    //         if ($laporanKegiatan->suratKeluar) {
    //             $laporanKegiatan->suratKeluar->delete();
    //         }
    //     });
    // }
}
