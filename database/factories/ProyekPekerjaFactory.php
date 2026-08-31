<?php

namespace Database\Factories;

use App\Enum\StatusPekerja;
use App\Models\Proyek;
use App\Models\ProyekPekerja;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProyekPekerja>
 */
class ProyekPekerjaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'proyek_id' => Proyek::factory(),
            'nama_pekerja' => fake()->name(),
            'nomor_hp' => fake()->numerify('08##########'),
            'status_jabatan' => fake()->jobTitle(),
            'tarif_harian' => fake()->numberBetween(100000, 500000),
            'tarif_overtime' => fake()->numberBetween(10000, 50000),
            'catatan' => fake()->sentence(),
            'status' => StatusPekerja::AKTIF,
        ];
    }
}
