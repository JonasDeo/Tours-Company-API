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
            ['email' => env('ADMIN_SEED_EMAIL', 'admin@nativekilimanjaro.com')],
            [
                'name'     => env('ADMIN_SEED_NAME', 'Native Kilimanjaro Admin'),
                'password' => Hash::make(env('ADMIN_SEED_PASSWORD', 'ChangeMeNow123!')), // Change after first login.
            ]
        );

        $this->command->info(sprintf(
            'Admin created: %s / %s',
            env('ADMIN_SEED_EMAIL', 'admin@nativekilimanjaro.com'),
            env('ADMIN_SEED_PASSWORD', 'ChangeMeNow123!')
        ));
    }
}