<?php

namespace Modules\Store\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Store\Models\Theme;

class ThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $themes = [
            [
                'name' => [
                    'ar' => 'كلاسيكي',
                    'en' => 'Classic',
                ],
            ],
            [
                'name' => [
                    'ar' => 'حديث',
                    'en' => 'Modern',
                ],
            ],
            [
                'name' => [
                    'ar' => 'افتراضي',
                    'en' => 'Default',
                ],
            ],
        ];

        foreach ($themes as $theme) {
            Theme::create($theme);
        }
    }
}

