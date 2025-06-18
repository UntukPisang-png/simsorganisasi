<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\SuratEdaran;

class SuratEdaranFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SuratEdaran::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'kepada' => fake()->text(),
            'no_surat' => fake()->word(),
            'tahun_edaran' => fake()->numberBetween(-10000, 10000),
            'isi_surat' => fake()->text(),
            'tgl_surat' => fake()->date(),
            'jabatan_ttd' => fake()->word(),
            'nama_ttd' => fake()->word(),
        ];
    }
}
