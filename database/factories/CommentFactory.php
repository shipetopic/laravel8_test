<?php
 
namespace Database\Factories;
 
use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;
 
class CommentFactory extends Factory
{
    // protected $model = \App\Models\Comment::class;

    public function definition()
    {
        return [
            'content' => $this->faker->text()
        ];
    }
}


/*
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    public function definition()
    {
        return [
            //
        ];
    }
}
*/
