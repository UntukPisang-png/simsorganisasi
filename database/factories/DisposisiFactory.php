<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Disposisi;
use App\Models\Pegawai;
use App\Models\SuratMasuk;

class DisposisiFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Disposisi::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'sifat' => fake()->randomElement(["Sangat"]),
            'tindakan' => fake()->randomElement(["Tanggapan"]),
            'catatan' => fake()->word(),
            'surat_masuk_id' => SuratMasuk::factory(),
            'pegawai_id' => Pegawai::factory(),
        ];
    }
}
