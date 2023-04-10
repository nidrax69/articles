<?php

namespace Database\Factories;

use App\Models\User;
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
    public function definition()
    {
        return [
            'title' => fake()->word(),
            'content' => fake()->text(),
            'status' => fake()->randomElement(['draft', 'published']),
            'published_at' => fake()->dateTimeBetween('-1 week', 'now'),
            'author_id' => function () {
                return User::factory()->create()->id;
            },
        ];
    }
}
