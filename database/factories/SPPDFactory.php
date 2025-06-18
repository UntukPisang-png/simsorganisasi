<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Pegawai;
use App\Models\SPPD;
use App\Models\SuratTugas;

class SPPDFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SPPD::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'nomor' => fake()->word(),
            'perintah_dari' => fake()->word(),
            'maksud' => fake()->text(),
            'berangkat' => fake()->word(),
            'tujuan' => fake()->word(),
            'angkutan' => fake()->word(),
            'lama_perjadin' => fake()->word(),
            'tgl_berangkat' => fake()->date(),
            'tgl_kembali' => fake()->date(),
            'pengikut' => fake()->text(),
            'tgl_lahir' => fake()->date(),
            'keterangan_pengikut' => fake()->text(),
            'bebasbiaya_instansi' => fake()->word(),
            'bebasbiaya_akun' => fake()->word(),
            'keterangan' => fake()->text(),
            'tgl_surat' => fake()->date(),
            'jabatan_ttd' => fake()->word(),
            'nama_ttd' => fake()->word(),
            'pangkat_ttd' => fake()->word(),
            'nip_ttd' => fake()->word(),
            'catatan_lembar2' => fake()->text(),
            'pegawai_id' => Pegawai::factory(),
            'surat_tugas_id' => SuratTugas::factory(),
        ];
    }
}
