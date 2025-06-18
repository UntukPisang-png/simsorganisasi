<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\NotaDinas;

class NotaDinasFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = NotaDinas::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'kepada' => fake()->text(),
            'dari' => fake()->text(),
            'tembusan' => fake()->text(),
            'tgl_surat' => fake()->date(),
            'no_surat' => fake()->word(),
            'sifat' => fake()->word(),
            'lampiran' => fake()->word(),
            'perihal' => fake()->word(),
            'isi_surat' => fake()->text(),
            'detail_surat' => fake()->text(),
            'penutup' => fake()->text(),
            'paraf' => fake()->text(),
            'jabatan_ttd' => fake()->word(),
            'nama_ttd' => fake()->word(),
            'pangkat' => fake()->word(),
            'golongan' => fake()->word(),
            'nip_ttd' => fake()->word(),
        ];
    }
}
