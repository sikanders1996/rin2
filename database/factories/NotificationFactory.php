<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => fake()->randomElement(['marketing', 'invoices', 'system']),
            'user_id'=>fake()->numberBetween(1, 20),
            'text' =>fake()->sentence,
            'expiration' =>fake()->dateTimeBetween('now', '+1 month'),
            'expiration' => fake()->dateTimeBetween('now', '+1 year'),
            'destination_type' => fake()->randomElement(['user', 'all']),
            'destination_id' => fake()->numberBetween(1, 100),
            'read_at' => null,
        ];
    }
}
