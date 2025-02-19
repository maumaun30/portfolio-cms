<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'devgdi.sw@gmail.com',
            'password' => Hash::make('qw3rty12e45')
        ]);

        $this->call(CategorySeeder::class);
        $this->call(PostSeeder::class);
    }
}
