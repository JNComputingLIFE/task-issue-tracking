<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Project;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {   
        // Global testing users..
        User::factory()->create([
            'name' => 'Demo Owner',
            'email' => 'owner@example.com',
        ]);

        User::factory()->count(4)->create();

        // The taglist seeding process (involves randomized tag coloring and naming)
        collect(['Bug', 'Feature', 'Refactor', 'Documentation', 'Security'])
            ->each(fn ($name) => Tag::firstOrCreate([
                'name' => $name,
                'color' => '#' . str_pad(dechex(mt_rand(0x000000, 0xFFFFFF)), 6, '0', STR_PAD_LEFT)
            ]));

        // Last step cascades down to seed issues, comments, tags, and users
        Project::factory()->count(3)->create();
    }
}