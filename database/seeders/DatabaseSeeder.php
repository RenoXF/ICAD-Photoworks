<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory(1, [
            'email' => 'admin@gmail.com',
            'role' => RoleEnum::Admin,
        ])->create();

        User::factory(1, [
            'email' => 'owner@gmail.com',
            'role' => RoleEnum::Owner,
        ])->create();

        User::factory(1, [
            'email' => 'client@gmail.com',
            'role' => RoleEnum::Client,
        ])->create();

        $this->call([
            AddonSeeder::class,
            ProductSeeder::class,
        ]);
    }
}
