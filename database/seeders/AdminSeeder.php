<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email' => 'admin@balbinasafaris.com'],
            [
                'name'     => 'Balbina Admin',
                'password' => Hash::make('balbina2024'),  // Change after first login!
            ]
        );

        $this->command->info('Admin created: admin@balbinasafaris.com / balbina2024');
    }
}