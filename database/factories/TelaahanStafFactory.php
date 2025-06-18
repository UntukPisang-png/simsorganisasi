<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\TelaahanStaf;

class TelaahanStafFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TelaahanStaf::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'kepada' => fake()->text(),
            'dari' => fake()->text(),
            'tgl_surat' => fake()->date(),
            'no_surat' => fake()->word(),
            'lampiran' => fake()->word(),
            'perihal' => fake()->word(),
            'persoalan' => fake()->text(),
            'praanggapan' => fake()->text(),
            'fakta' => fake()->text(),
            'analisis' => fake()->text(),
            'saran' => fake()->text(),
            'jabatan_ttd' => fake()->word(),
            'nama_ttd' => fake()->word(),
            'nip_ttd' => fake()->word(),
        ];
    }
}
