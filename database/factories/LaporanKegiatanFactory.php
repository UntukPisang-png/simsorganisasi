<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\LaporanKegiatan;

class LaporanKegiatanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LaporanKegiatan::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'no_laporan' => fake()->word(),
            'umum' => fake()->text(),
            'landasan' => fake()->text(),
            'maksud' => fake()->text(),
            'kegiatan' => fake()->text(),
            'hasil' => fake()->text(),
            'kesimpulan' => fake()->text(),
            'penutup' => fake()->text(),
            'paraf' => fake()->text(),
            'jabatan_ttd' => fake()->word(),
            'nama_ttd' => fake()->word(),
            'nip_ttd' => fake()->word(),
        ];
    }
}
