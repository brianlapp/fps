<?php

namespace Database\Seeders;

use App\Models\Admin;
use Database\Seeders\MinimalWorkingSeeder;
use Database\Seeders\SiteConfigSeeder;
use Database\Seeders\TestDataSeeder;
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

        Admin::create([
            'name' => 'Marjan Pahor',
            'slug' => 'marjan-pahor',
            'email' => 'marjan.pahor.86@gmail.com',
            'role' => 1,
            'is_super' => true,
            'password' => Hash::make('admin#1')
        ]);

        Admin::create([
            'name' => 'Nikanor Shagar',
            'slug' => 'nikanor-shagar',
            'email' => 'nikanorshagar@gmail.com',
            'role' => 1,
            'is_super' => true,
            'password' => Hash::make('admin#1')
        ]);
        
        // Only run the TestDataSeeder to create test data for UI development
        $this->call(TestDataSeeder::class);
    }
}
