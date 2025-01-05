<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\GroupUser;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class GroupRequestToJoinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groups = Group::inRandomOrder()->limit(10)->get();
        $users = User::all();

        foreach ($groups as $group) {
            $requestCount = rand(2, 7);

            foreach ($users->random($requestCount) as $user) {
                $isMember = GroupUser::where('group_id', $group->id)
                    ->where('user_id', $user->id)
                    ->exists();

                if (!$isMember) {
                    // إنشاء طلب انضمام
                    DB::table('group_request_to_join')->insert([
                        'group_id' => $group->id,
                        'user_id' => $user->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
