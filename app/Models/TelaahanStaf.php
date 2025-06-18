<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelaahanStaf extends Model
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
        'tgl_surat',
        'no_surat',
        'lampiran',
        'perihal',
        'persoalan',
        'praanggapan',
        'fakta',
        'analisis',
        'kesimpulan',
        'saran',
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
    ];

    public function suratKeluar()
    {
        return $this->belongsTo(SuratKeluar::class, 'surat_keluar_id');
    }

    protected static function booted()
    {
        static::deleting(function ($telaahanStaf) {
            if ($telaahanStaf->suratKeluar) {
                $telaahanStaf->suratKeluar->delete();
            }
        });
    }
}
