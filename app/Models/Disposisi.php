<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Disposisi extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sifat',
        'tindakan',
        'catatan',
        'surat_masuk_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'surat_masuk_id' => 'integer',
    ];

    public function suratMasuk(): BelongsTo
    {
        return $this->belongsTo(SuratMasuk::class, 'surat_masuk_id');
    }

    public function pegawai(): BelongsToMany
    {
        return $this->belongsToMany(Pegawai::class, 'disposisi_pegawai');
    }

    protected static function booted()
    {
        static::created(function ($disposisi) {
            if ($disposisi->surat_masuk_id) {
                \App\Models\SuratMasuk::where('id', $disposisi->surat_masuk_id)
                    ->update(['status_disposisi' => 'Sudah Disposisi']);
                }
            });

        static::deleted(function ($disposisi) {
            if ($disposisi->surat_masuk_id) {
                \App\Models\SuratMasuk::where('id', $disposisi->surat_masuk_id)
                    ->update(['status_disposisi' => 'Belum Disposisi']);
                }
        });
    }
}
