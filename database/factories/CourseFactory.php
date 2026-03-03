<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(3);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'short_description' => fake()->sentence(10),
            'description' => fake()->paragraphs(3, true),
            'price' => fake()->randomFloat(2, 9.99, 99.99),
            'estimated_duration_weeks' => fake()->numberBetween(4, 12),
            'is_active' => true,
            'order_index' => fake()->numberBetween(1, 100),
            'total_students' => fake()->numberBetween(0, 500),
            'average_rating' => fake()->randomFloat(2, 3.5, 5.0),
            'total_reviews' => fake()->numberBetween(0, 100),
        ];
    }
}