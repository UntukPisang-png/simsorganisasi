<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\SuratKeluar;
use App\Models\SuratUndangan;

class SuratUndanganFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SuratUndangan::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'no_surat' => fake()->word(),
            'lampiran' => fake()->word(),
            'perihal' => fake()->word(),
            'tgl_surat' => fake()->date(),
            'kepada' => fake()->word(),
            'di' => fake()->word(),
            'isi_surat' => fake()->text(),
            'tgl_undangan' => fake()->date(),
            'tempat_undangan' => fake()->word(),
            'waktu_undangan' => fake()->time(),
            'penutup' => fake()->text(),
            'nama_ttd' => fake()->word(),
            'jabatan_ttd' => fake()->word(),
            'nip_ttd' => fake()->word(),
            'surat_keluar_id' => SuratKeluar::factory(),
        ];
    }
}
