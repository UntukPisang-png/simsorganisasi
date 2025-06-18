<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\BiayaPerjadin;
use App\Models\BiayaRiil;
use App\Models\SPPD;

class BiayaRiilFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BiayaRiil::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'pengeluaran' => fake()->text(),
            's_p_p_d_id' => SPPD::factory(),
            'biaya_perjadin_id' => BiayaPerjadin::factory(),
        ];
    }
}
