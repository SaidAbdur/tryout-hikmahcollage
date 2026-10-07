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
            ['email' => 'admin@HikmahCollage.id'],
            ['name' => 'Administrator', 'password' => bcrypt('AdminTryoutHC')]
        );
    }
}
