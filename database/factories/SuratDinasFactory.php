<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\SuratDinas;

class SuratDinasFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SuratDinas::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'no_surat' => fake()->word(),
            'tgl_surat' => fake()->date(),
            'sifat' => fake()->word(),
            'lampiran' => fake()->word(),
            'perihal' => fake()->word(),
            'kepada' => fake()->word(),
            'tempat' => fake()->word(),
            'isi_surat' => fake()->text(),
            'jabatan_ttd' => fake()->word(),
            'nama_ttd' => fake()->word(),
            'nip_ttd' => fake()->word(),
        ];
    }
}
