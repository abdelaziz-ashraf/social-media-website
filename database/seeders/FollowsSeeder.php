<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FollowsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            $followers = $users->random(rand(5, 15));
            foreach ($followers as $follower) {
                if ($user->id !== $follower->id) {
                    $user->followers()->attach($follower->id);
                }
            }
        }
    }
}
