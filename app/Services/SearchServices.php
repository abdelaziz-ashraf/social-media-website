<?php

namespace App\Services;

use App\Models\Post;
use App\Models\PostTag;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class SearchServices
{

    public function searchByTag(string $tag) {
        return Cache::remember("search_tag_{$tag}", 60 * 24, function() use ($tag) {
            $tagId = Tag::where('name', $tag)->pluck('id')->first();
            $postsIds = PostTag::where('tag_id', $tagId)->pluck('post_id')->toArray();
            return Post::whereIn('id', $postsIds)->paginate();
        });
    }

    public function fullTextPostSearch(string $text) {
        return Cache::remember("full_text_search_{$text}", 60 * 24, function() use ($text) {
            return Post::search($text)->paginate();
        });
    }

    public function userSearchByName(string $name) {
        return Cache::remember("user_search_{$name}", 60 * 24, function() use ($name) {
            return User::where('name', 'like', $name . '%')
                ->orderBy('number_of_followers', 'desc')
                ->get();
        });
    }
}
