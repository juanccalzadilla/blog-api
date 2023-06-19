<?php

namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake('es_ES')->sentence(),
            'body' => fake('es_ES')->paragraph(),
            'user_id' => fake()->numberBetween(1, 10),
            'category_id' => fake()->numberBetween(1, 3),
            'image' => 'https://picsum.photos/1200/400?random=1',
        ];
    }
}
