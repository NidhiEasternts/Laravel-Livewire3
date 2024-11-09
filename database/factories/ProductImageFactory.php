<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ProductImage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductImage>
 */
class ProductImageFactory extends Factory
{
    protected $model = ProductImage::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $imageName = Str::random(10) . '.jpg';
        Storage::disk('public')->put('product_images/' . $imageName, file_get_contents($this->faker->imageUrl()));

        return [
            'product_id' => 1, // You can set this dynamically later
            'image' => 'product_images/' . $imageName,
        ];
    }
}
