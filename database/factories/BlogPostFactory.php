<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BlogPostFactory extends Factory
{
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(10),
            'content' => $this->faker->paragraphs(5, true)
        ];
    }

    public function newTitle()
    {
        return $this->state(function (array $attributes) {
            return [
                'title' => 'New Title',
                // 'content' => 'Content of the blog post'
            ];
        });
    }

    // public function configure()
    // {
    //     return [
    //         'title' => 'New title',
    //         'content' => 'Content of the blog post'
    //     ];
    // }
}