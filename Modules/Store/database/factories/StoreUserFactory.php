<?php

namespace Modules\Store\Database\Factories;

use Modules\User\Models\User;
use Modules\Store\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

class StoreUserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Store\Models\StoreUser::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'store_id' => Store::factory(),
            'user_id' => User::factory(),
            'is_active' => true,
        ];
    }
}

