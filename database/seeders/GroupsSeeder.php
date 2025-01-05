<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\GroupUser;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GroupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $groups = Group::factory(20)->create();

        foreach ($groups as $group) {
            $owner = $users->random();
            $group->users()->attach($owner->id, ['role' => 'owner']);
        }

        foreach ($users as $user) {
            $randomGroups = $groups->random(rand(3, 5));
            foreach ($randomGroups as $group) {
                GroupUser::updateOrCreate(
                    ['user_id' => $user->id, 'group_id' => $group->id],
                    ['role' => 'user']
                );
            }
        }
    }
}
