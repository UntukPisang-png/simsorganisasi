<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Pegawai;
use App\Models\SPPD;
use App\Models\biaya_perjadin;

class BiayaPerjadinFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BiayaPerjadin::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'uang_harian' => fake()->word(),
            'penginapan' => fake()->text(),
            'transportasi' => fake()->text(),
            's_p_p_d_id' => SPPD::factory(),
            'pegawai_id' => Pegawai::factory(),
        ];
    }
}
