<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\StatusDisposisi;
use App\Http\Controllers\StatusDisposisiController;

class SuratMasuk extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'no_suratmasuk',
        'tgl_suratmasuk',
        'tgl_diterima',
        'pengirim',
        'perihal',
        'file_suratmasuk',
        'status_disposisi',
        'kategori_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'tgl_suratmasuk' => 'date',
        'tgl_diterima' => 'date',
        'kategori_id' => 'integer',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    public function disposisis(): HasMany
    {
        return $this->hasMany(Disposisi::class, 'surat_masuk_id');
    }

    // protected static function booted()
    // {
    //     static::deleting(function ($suratMasuk) {
    //         if ($suratMasuk->disposisi) {
    //             $suratMasuk->disposisi->delete();
    //         }
    //     });
    // }

}
