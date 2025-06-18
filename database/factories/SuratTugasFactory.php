<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Pegawai;
use App\Models\SuratTugas;

class SuratTugasFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SuratTugas::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'no_surat' => fake()->word(),
            'tgl_surat' => fake()->date(),
            'kepada' => fake()->word(),
            'isi_surat' => fake()->text(),
            'penutup' => fake()->text(),
            'nama_ttd' => fake()->word(),
            'jabatan_ttd' => fake()->word(),
            'nip_ttd' => fake()->word(),
            'pegawai_id' => Pegawai::factory(),
        ];
    }
}
