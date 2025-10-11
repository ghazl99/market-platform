<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\User\Models\User;
use Modules\Store\Models\Store;
use Spatie\Permission\Models\Role;

class CustomerWithBirthDateSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Get or create customer role
        $customerRole = Role::firstOrCreate(['name' => 'customer']);

        // Get first store
        $store = Store::first();

        if (!$store) {
            $this->command->error('No store found. Please create a store first.');
            return;
        }

        // Create customers with birth dates
        $customers = [
            [
                'name' => 'أحمد محمد',
                'email' => 'ahmed@example.com',
                'phone' => '+966501234567',
                'birth_date' => '1990-05-15',
                'address' => 'الرياض، المملكة العربية السعودية',
                'city' => 'الرياض',
                'postal_code' => '12345',
                'country' => 'SA',
                'language' => 'ar',
                'timezone' => 'Asia/Riyadh',
                'email_notifications' => true,
                'sms_notifications' => true,
            ],
            [
                'name' => 'فاطمة علي',
                'email' => 'fatima@example.com',
                'phone' => '+966509876543',
                'birth_date' => '1985-12-20',
                'address' => 'جدة، المملكة العربية السعودية',
                'city' => 'جدة',
                'postal_code' => '54321',
                'country' => 'SA',
                'language' => 'ar',
                'timezone' => 'Asia/Riyadh',
                'email_notifications' => false,
                'sms_notifications' => true,
            ],
            [
                'name' => 'محمد السعد',
                'email' => 'mohammed@example.com',
                'phone' => '+966507654321',
                'birth_date' => '1992-08-10',
                'address' => 'الدمام، المملكة العربية السعودية',
                'city' => 'الدمام',
                'postal_code' => '67890',
                'country' => 'SA',
                'language' => 'ar',
                'timezone' => 'Asia/Riyadh',
                'email_notifications' => true,
                'sms_notifications' => false,
            ]
        ];

        foreach ($customers as $customerData) {
            $user = User::create([
                'name' => $customerData['name'],
                'email' => $customerData['email'],
                'password' => bcrypt('password123'),
                'phone' => $customerData['phone'],
                'birth_date' => $customerData['birth_date'],
                'address' => $customerData['address'],
                'city' => $customerData['city'],
                'postal_code' => $customerData['postal_code'],
                'country' => $customerData['country'],
                'language' => $customerData['language'],
                'timezone' => $customerData['timezone'],
                'email_notifications' => $customerData['email_notifications'],
                'sms_notifications' => $customerData['sms_notifications'],
                'email_verified_at' => now(),
            ]);

            // Assign customer role
            $user->assignRole($customerRole);

            // Attach to store
            $user->stores()->attach($store->id);

            $this->command->info("Created customer: {$user->name} with birth date: {$user->birth_date}");
        }

        $this->command->info('Customers with birth dates created successfully!');
    }
}
