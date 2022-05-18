<?php

namespace Database\Factories;

use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuthorFactory extends Factory
{
    public function definition()
    {
        return [
            //
        ];
    }

    public function newProfile()
    {
        return $this->afterCreating(function($author) {
            $author->profile()->save(Profile::factory()->make());
        });

        // return $this->afterMaking(function($author) {
        //     $author->profile()->save(Profile::factory()->make());
        // });
    }
}
