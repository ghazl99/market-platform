<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Group;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default group
        Group::create([
            'name' => 'المجموعة الافتراضية',
            'profit_percentage' => 0.00,
            'is_default' => true,
        ]);

        // Create some sample groups
        Group::create([
            'name' => 'المجموعة الفضية',
            'profit_percentage' => 5.00,
            'is_default' => false,
        ]);

        Group::create([
            'name' => 'المجموعة الذهبية',
            'profit_percentage' => 10.00,
            'is_default' => false,
        ]);

        Group::create([
            'name' => 'المجموعة الماسية',
            'profit_percentage' => 15.00,
            'is_default' => false,
        ]);
    }
}
