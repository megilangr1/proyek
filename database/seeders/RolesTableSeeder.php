<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'meggi',
                'guard_name' => 'web',
            ],
            [
                'name' => 'administrator',
                'guard_name' => 'web',
            ],
            [
                'name' => 'operator',
                'guard_name' => 'web',
            ],
        ];

        try {
            foreach ($data as $key => $value) {
                Role::firstOrCreate($value);
            }
        } catch (\Throwable $th) {
            // throw $th;
        }
    }
}
