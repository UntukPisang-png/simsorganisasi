<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Pegawai;
use App\Models\SuratTugas;
use App\Models\TugasPegawai;

class TugasPegawaiFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TugasPegawai::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'pegawai_id' => Pegawai::factory(),
            'surat_tugas_id' => SuratTugas::factory(),
        ];
    }
}
