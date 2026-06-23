<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

//project needs
use App\Models\Project;
use App\Models\Issue;
use App\Models\Tag;
use App\Models\Comment;

class DatabaseSeeder extends Seeder
{
  public function run(): void
{   //note: make a taglist, then create the tag per type.. 
    $tags = collect(['Bug', 'Feature', 'Refactor', 'Documentation', 'Security'])
        ->map(fn ($name) => Tag::firstOrCreate([
            'name' => $name,
            'color' => '#' . str_pad(dechex(mt_rand(0x000000, 0xFFFFFF)), 6, '0', STR_PAD_LEFT)
        ]));

    Project::factory()->count(3)->create();
}
}
