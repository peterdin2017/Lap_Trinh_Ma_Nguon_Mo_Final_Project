<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('local')) {
            Account::firstOrCreate(
                ['email' => 'admin.demo@example.com', 'role' => 'admin'],
                ['password_hash' => Hash::make('AdminDemo@2026!'), 'status' => 'active', 'is_verified' => true]
            );
        }
        $this->call([
            StoreCatalogSeeder::class,
            AdminDemoSeeder::class,
        ]);
    }
}
