<?php

namespace Database\Factories;

use App\Models\Issue;
use App\Models\Comment;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

class IssueFactory extends Factory
{
    protected $model = Issue::class;

    public function definition(): array
    {
        return [
            'project_id' => null, // Overridden by ProjectFactory or Seeder
            'title' => $this->faker->sentence(6),
            'description' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(['open', 'in_progress', 'closed']),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
            'due_date' => $this->faker->optional()->date(),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Issue $issue) {
            Comment::factory()
                ->count(rand(2, 5))
                ->create(['issue_id' => $issue->id]);

            $tags = Tag::inRandomOrder()->take(rand(1, 3))->pluck('id');
            
            if ($tags->isNotEmpty()) {
                $issue->tags()->attach($tags);
            }
        });
    }
}