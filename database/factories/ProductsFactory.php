<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Products;
use App\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Products>
 */
class ProductsFactory extends Factory
{
    protected $model = Products::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_name' => $this->faker->word,
            'price' => $this->faker->randomFloat(2, 10, 1000), // Random price between 10 and 1000
            'category_id' => Category::factory(), // Assuming you have a Category factory
            'user_id' => 1, // Replace with an actual user ID if needed
        ];
    }
}
