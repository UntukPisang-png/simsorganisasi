<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratKeluar extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'no_suratkeluar',
        'tgl_suratkeluar',
        'perihal',
        'lampiran',
        'tujuan',
        'file_suratkeluar',
        'kategori_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'tgl_suratkeluar' => 'date',
        'kategori_id' => 'integer',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }
    public function suratDinas()
    {
        return $this->hasMany(SuratDinas::class, 'surat_keluar_id');
    }
    public function telaahanStaf()
    {
        return $this->hasMany(TelaahanStaf::class, 'surat_keluar_id');
    }
    public function suratUndangan()
    {
        return $this->hasMany(SuratUndangan::class, 'surat_keluar_id');
    }
    public function notaDinas()
    {
        return $this->hasMany(NotaDinas::class, 'surat_keluar_id');
    }
    public function suratTugas()
    {
        return $this->hasMany(SuratTugas::class, 'surat_keluar_id');
    }
    public function suratPengantar()
    {
        return $this->hasMany(SuratPengantar::class, 'surat_keluar_id');
    }
    public function suratEdaran()
    {
        return $this->hasMany(SuratEdaran::class, 'surat_keluar_id');
    }
    
    protected static function booted()
    {
        static::deleting(function ($suratKeluar) {
            $suratKeluar->suratDinas()->delete();
            $suratKeluar->notaDinas()->delete();
            $suratKeluar->suratTugas()->delete();
            $suratKeluar->telaahanStaf()->delete();
            $suratKeluar->suratUndangan()->delete();
            $suratKeluar->suratPengantar()->delete();
            $suratKeluar->suratEdaran()->delete();
            // Tambahkan relasi lain jika ada
        });
    }
}
