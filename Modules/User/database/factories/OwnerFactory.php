<?php

namespace Modules\User\Database\Factories;

use Modules\User\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

class OwnerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('12345678'), // كلمة سر افتراضية
            'email_verified_at' => now(),
            'last_login_at' => null,
            'last_updated_at_password' => null
        ];
    }
}
