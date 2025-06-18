<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Kategori;
use App\Models\SuratKeluar;

class SuratKeluarFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SuratKeluar::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'no_suratkeluar' => fake()->word(),
            'tgl_suratkeluar' => fake()->date(),
            'perihal' => fake()->word(),
            'lampiran' => fake()->word(),
            'tujuan' => fake()->word(),
            'file_suratkeluar' => fake()->word(),
            'kategori_id' => Kategori::factory(),
        ];
    }
}
