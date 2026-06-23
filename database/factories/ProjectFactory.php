<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Issue;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'start_date' => $this->faker->date(),
            'deadline' => $this->faker->date(),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Project $project) {
            Issue::factory()
                ->count(5)
                ->create(['project_id' => $project->id]);
        });
    }
}