<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
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
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $title = $this->faker->sentence,
            'slug' => Str::slug($title),
            'body' => $this->faker->paragraphs(5, true),
            'excerpt' => $this->faker->text(150),
            'image_path' => 1,
            'author_id' => 1, // Assuming an author with ID 1 exists
            'published_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'status' => 'published',
            'tags' => json_encode($this->faker->words(3)), // Convert tags array to JSON
        ];
    }

    public function withCategories($count = 1)
    {
        return $this->afterCreating(function (Post $post) use ($count) {
            // Attach random categories to the post
            $categoryIds = Category::inRandomOrder()->take($count)->pluck('id');
            $post->categories()->attach($categoryIds);
        });
    }
}
