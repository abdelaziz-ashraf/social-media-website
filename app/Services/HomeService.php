<?php

namespace App\Services;

use App\Models\GroupUser;
use App\Models\Post;
use Illuminate\Support\Facades\Cache;

class HomeService
{
    public function getUserFeed () {
        $userId = auth()->id();
        return Cache::remember("user-$userId-feed", 60 * 24, function () use ($userId) {
            $userGroupIds = GroupUser::where('user_id', $userId)
                ->pluck('group_id')->toArray();

            $followerIds = auth()->user()->followers()->pluck('users.id')->toArray();

            return Post::withTrashed()->where(function ($query) use ($userGroupIds, $followerIds) {
                $query->whereIn('group_id', $userGroupIds);

                $query->orWhere(function ($subQuery) use ($followerIds, $userGroupIds) {
                    $subQuery->whereIn('user_id', $followerIds)
                        ->whereNotIn('group_id', $userGroupIds);
                });
            })->when(request('filter') === 'popular', function ($query) {
                $query->orderBy('number_of_likes', 'desc')
                    ->orderBy('number_of_comments', 'desc');
            })->paginate();
        });
    }
}
