<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentsAndLikesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $posts = Post::all();
        $users = User::all();

        foreach ($posts as $post) {
            $eligibleUsers = [];

            if ($post->group_id) {
                $eligibleUsers = $post->group->users()->pluck('users.id')->toArray();
            }

            $followers = $post->user->followers()->pluck('users.id')->toArray();
            $eligibleUsers = array_merge($eligibleUsers, $followers);
            $eligibleUsers = array_unique($eligibleUsers);

            if (count($eligibleUsers) > 0) {
                $randomUsers = array_rand($eligibleUsers, min(rand(1, 5), count($eligibleUsers)));
                if (is_array($randomUsers)) {
                    foreach ($randomUsers as $key) {
                        $userId = $eligibleUsers[$key];
                        if ($userId) {
                            Comment::create([
                                'user_id' => $userId,
                                'post_id' => $post->id,
                                'parent_id' => null,
                                'content' => fake()->sentence(),
                            ]);
                        }
                    }
                } else {
                    $userId = $eligibleUsers[$randomUsers];
                    if ($userId) {
                        Comment::create([
                            'user_id' => $userId,
                            'post_id' => $post->id,
                            'parent_id' => null,
                            'content' => fake()->sentence(),
                        ]);
                    }
                }

                $randomUsers = array_rand($eligibleUsers, min(rand(1, 5), count($eligibleUsers)));
                if (is_array($randomUsers)) {
                    foreach ($randomUsers as $key) {
                        $userId = $eligibleUsers[$key];
                        if ($userId) {
                            Like::updateOrCreate(
                                ['user_id' => $userId, 'post_id' => $post->id],
                                []
                            );
                        }
                    }
                } else {
                    $userId = $eligibleUsers[$randomUsers];
                    if ($userId) {
                        Like::updateOrCreate(
                            ['user_id' => $userId, 'post_id' => $post->id],
                            []
                        );
                    }
                }
            }
        }
    }
}
