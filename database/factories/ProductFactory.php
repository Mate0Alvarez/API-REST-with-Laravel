<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Product;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->word(),
            'description' => fake()->paragraph(1),
            'quantity' => fake()->numberBetween(1,10),
            'status' => fake()->randomElement([Product::PRODUCT_AVAILABLE, Product::PRODUCT_NOT_AVAILABLE]),
            'image' => fake()->randomElement(['shirt_1.jpg','shirt_2.jpg','shirt_3.jpg']),
            'seller_id' => User::all()->random()->id
        ];
    }
}
