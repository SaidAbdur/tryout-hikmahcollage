<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Change this password right after the first login.
        Admin::firstOrCreate(
            ['email' => 'admin@tryoutku.test'],
            ['name' => 'Administrator', 'password' => 'password'],
        );
    }
}
