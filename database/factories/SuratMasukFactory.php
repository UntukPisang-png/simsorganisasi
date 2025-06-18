<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Kategori;
use App\Models\SuratMasuk;

class SuratMasukFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SuratMasuk::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'no_suratmasuk' => fake()->word(),
            'tgl_suratmasuk' => fake()->date(),
            'tgl_diterima' => fake()->date(),
            'pengirim' => fake()->word(),
            'perihal' => fake()->word(),
            'file_suratmasuk' => fake()->word(),
            'status_disposisi' => fake()->numberBetween(-10000, 10000),
            'kategori_id' => Kategori::factory(),
        ];
    }
}
