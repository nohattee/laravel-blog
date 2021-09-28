<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $paragraphs = $this->faker->paragraphs(rand(5, 10));
        $content = '';
        foreach ($paragraphs as $para) {
            $content .= "<p>{$para}</p>";
        }
        return [
            'title' => $this->faker->sentence($nbWords = rand(6, 10), $variableNbWords = true),
            'content' => $content,
            'thumbnail' => $this->faker->imageUrl($width = 640, $height = 480),
            'author_id' => User::all()->random()->id,
            'post_status' => $this->faker->randomElement(['public','private']),
        ];
    }
}
