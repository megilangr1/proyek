<?php

namespace Database\Factories;

use App\Enums\KategoriPengeluaran;
use App\Enums\StatusPengeluaran;
use App\Models\Proyek;
use App\Models\ProyekPengeluaran;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProyekPengeluaran>
 */
class ProyekPengeluaranFactory extends Factory
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
            'tanggal' => fake()->date(),
            'kategori' => fake()->randomElement(KategoriPengeluaran::cases())->value,
            'nama_item' => fake()->words(3, true),
            'nominal' => fake()->numberBetween(50000, 5000000),
            'keterangan' => fake()->optional()->sentence(),
            'status' => fake()->randomElement(StatusPengeluaran::cases())->value,
        ];
    }
}
