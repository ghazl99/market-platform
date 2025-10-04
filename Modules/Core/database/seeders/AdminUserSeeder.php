<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\User\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::withoutEvents(function () {
            // إنشاء مستخدم admin
            User::create([
                'name' => 'Admin',
                'email' => 'admin@kaymn.store',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ])->assignRole('admin');
        });
    }
}
