<?php

namespace Database\Factories;

use App\Enums\StatusProyek;
use App\Models\Proyek;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Proyek>
 */
class ProyekFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tanggalMulai = fake()->dateTimeBetween('-1 year', '+1 year');
        $tanggalSelesai = fake()->dateTimeBetween($tanggalMulai, '+2 years');

        return [
            'kode_proyek' => 'PRJ'.str_pad((string) fake()->unique()->numberBetween(1, 998), 3, '0', STR_PAD_LEFT),
            'nama_proyek' => fake()->sentence(3),
            'pemilik' => fake()->company(),
            'lokasi' => fake()->address(),
            'tanggal_mulai' => $tanggalMulai->format('Y-m-d'),
            'tanggal_selesai' => $tanggalSelesai->format('Y-m-d'),
            'nilai_proyek' => fake()->randomFloat(2, 50000000, 5000000000),
            'status' => StatusProyek::AKTIF,
        ];
    }
}
