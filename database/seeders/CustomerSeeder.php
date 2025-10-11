<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\User\Models\User;
use Modules\Store\Models\Store;
use Spatie\Permission\Models\Role;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء دور العميل إذا لم يكن موجوداً
        $customerRole = Role::firstOrCreate(['name' => 'customer']);

        // الحصول على المتجر الأول
        $store = Store::first();

        if (!$store) {
            $this->command->error('No store found. Please create a store first.');
            return;
        }

        // بيانات العملاء للتجريب
        $customers = [
            [
                'name' => 'أحمد محمد',
                'email' => 'ahmed.mohamed@example.com',
                'phone' => '+966501234567',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
                'email_notifications' => true,
                'sms_notifications' => true,
            ],
            [
                'name' => 'فاطمة علي',
                'email' => 'fatima.ali@example.com',
                'phone' => '+966502345678',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
                'email_notifications' => true,
                'sms_notifications' => false,
            ],
            [
                'name' => 'محمد السعد',
                'email' => 'mohammed.alsad@example.com',
                'phone' => '+966503456789',
                'password' => bcrypt('password123'),
                'email_verified_at' => null,
                'email_notifications' => false,
                'sms_notifications' => true,
            ],
            [
                'name' => 'نورا أحمد',
                'email' => 'nora.ahmed@example.com',
                'phone' => '+966504567890',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
                'email_notifications' => true,
                'sms_notifications' => true,
            ],
            [
                'name' => 'خالد العتيبي',
                'email' => 'khalid.alotaibi@example.com',
                'phone' => '+966505678901',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
                'email_notifications' => false,
                'sms_notifications' => false,
            ],
        ];

        foreach ($customers as $customerData) {
            // إنشاء المستخدم
            $user = User::create($customerData);

            // إعطاء المستخدم دور العميل
            $user->assignRole($customerRole);

            // ربط المستخدم بالمتجر
            $user->stores()->attach($store->id, [
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->command->info("Created customer: {$user->name} ({$user->email})");
        }

        $this->command->info('Successfully created ' . count($customers) . ' customers!');
    }
}
