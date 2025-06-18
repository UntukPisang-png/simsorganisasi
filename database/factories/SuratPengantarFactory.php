<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Pegawai;
use App\Models\SuratPengantar;

class SuratPengantarFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SuratPengantar::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'no_surat' => fake()->word(),
            'tgl_surat' => fake()->date(),
            'kepada' => fake()->word(),
            'di' => fake()->word(),
            'naskah_dinas' => fake()->word(),
            'jumlah' => fake()->numberBetween(-10000, 10000),
            'keterangan' => fake()->text(),
            'tgl_diterima' => fake()->date(),
            'penerima' => fake()->word(),
            'pengirim' => fake()->word(),
            'pegawai_id' => Pegawai::factory(),
        ];
    }
}
