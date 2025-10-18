<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'     => 'Admin',
                'password' => bcrypt('admin'),
            ]
        );

        $admin->syncRoles([
            RoleEnum::ADMIN->value,
            RoleEnum::USER->value,
        ]);
    }
}
