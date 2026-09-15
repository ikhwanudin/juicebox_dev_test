<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    protected static ?string $user;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        $title = fake()->sentence();

        return [
            "slug" => Str::slug($title),
            "title" => $title,
            "body" => fake()->paragraph,
            'user_id' => User::factory()
        ];
    }
}
