<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Administrator',
                'email' => 'admin@mail.com',
                'password' => Hash::make('admin123'),
                'roles' => ['administrator'],
            ],
            [
                'name' => 'Operator',
                'email' => 'test@mail.com',
                'password' => Hash::make('test1234'),
                'roles' => ['operator'],
            ],
        ];

        try {
            foreach ($data as $key => $value) {
                $roles = $value['roles'];
                unset($value['roles']);

                $data = User::firstOrCreate([
                    'email' => $value['email'],
                ], [
                    ...$value,
                ]);

                $data->syncRoles($roles);
            }
        } catch (\Throwable $th) {
            // throw $th;
        }
    }
}
