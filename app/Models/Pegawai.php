<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class Pegawai extends Model
{
    use HasFactory;
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama',
        'nip',
        'golongan',
        'pangkat',
        'jabatan',
        'divisi',
        'no_hp',
        'user_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function disposisis(): BelongsToMany
    {
        return $this->belongsToMany(Disposisi::class, 'disposisi_pegawai' );
    }

    public function suratTugas(): BelongsToMany
    {
        return $this->belongsToMany(SuratTugas::class, 'tugas_pegawais' );
    }

    public function laporanKegiatan()
    {
        return $this->hasMany(LaporanKegiatan::class);
    }

    public function sPPD()
    {
        return $this->hasMany(SPPD::class);
    }
}
