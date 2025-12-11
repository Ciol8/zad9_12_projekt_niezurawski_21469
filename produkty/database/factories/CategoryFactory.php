<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            // unique() zapewni, że przy tworzeniu 100 kategorii, każda nazwa będzie inna
            'name' => fake()->unique()->words(2, true),
            'description' => fake()->sentence(),
        ];
    }
}