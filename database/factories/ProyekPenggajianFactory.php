<?php

namespace Database\Factories;

use App\Enum\StatusPenggajian;
use App\Models\Proyek;
use App\Models\ProyekPenggajian;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProyekPenggajian>
 */
class ProyekPenggajianFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $periodeMulai = fake()->dateTimeBetween('-1 month', '+1 month');
        $periodeSelesai = (clone $periodeMulai)->modify('+'.fake()->numberBetween(1, 7).' days');

        return [
            'proyek_id' => Proyek::factory(),
            'nama_periode' => 'Minggu '.fake()->numberBetween(1, 52),
            'periode_mulai' => $periodeMulai->format('Y-m-d'),
            'periode_selesai' => $periodeSelesai->format('Y-m-d'),
            'jam_kerja' => fake()->numberBetween(1, 255),
            'keterangan' => fake()->sentence(),
            'status' => StatusPenggajian::AKTIF,
        ];
    }
}
