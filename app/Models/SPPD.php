<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SPPD extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nomor',
        'perintah_dari',
        'maksud',
        'berangkat',
        'tujuan',
        'angkutan',
        'lama_perjadin',
        'tgl_berangkat',
        'tgl_kembali',
        'pengikut',
        'tgl_lahir',
        'keterangan_pengikut',
        'bebasbiaya_instansi',
        'bebasbiaya_akun',
        'keterangan',
        'tgl_surat',
        'catatan_lembar2',
        'nama_ttd',
        'jabatan_ttd',
        'pangkat_ttd',
        'nip_ttd',
        'status_perjalanan',
        'pegawai_id',
        'surat_tugas_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'tgl_berangkat' => 'date',
        'tgl_kembali' => 'date',
        'pengikut' => 'array',
        'tgl_lahir' => 'date',
        'tgl_surat' => 'date',
        'pegawai_id' => 'integer',
        'surat_tugas_id' => 'integer',
    ];

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function suratTugas(): BelongsTo
    {
        return $this->belongsTo(SuratTugas::class);
    }
    public function suratKeluar()
    {
        return $this->belongsTo(SuratKeluar::class, 'surat_keluar_id');
    }

    // protected static function booted()
    // {
    //     static::deleting(function ($sPPD) {
    //         if ($sPPD->suratKeluar) {
    //             $sPPD->suratKeluar->delete();
    //         }
    //     });
    // }
}
