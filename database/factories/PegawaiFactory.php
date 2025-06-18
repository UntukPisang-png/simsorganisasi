<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Pegawai;
use App\Models\User;

class PegawaiFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Pegawai::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->regexify('[A-Za-z0-9]{100}'),
            'nip' => fake()->numberBetween(-10000, 10000),
            'golongan' => fake()->word(),
            'pangkat' => fake()->word(),
            'divisi' => fake()->word(),
            'user_id' => User::factory(),
        ];
    }
}
