<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            $groups = $user->groups;

            for ($i = 0; $i < 3; $i++) {
                Post::create([
                    'user_id' => $user->id,
                    'group_id' => $groups->random()->id,
                    'content' => fake()->sentence(),
                ]);

                Post::create([
                    'user_id' => $user->id,
                    'group_id' => null,
                    'content' => fake()->sentence(),
                ]);
            }
        }
    }
}
