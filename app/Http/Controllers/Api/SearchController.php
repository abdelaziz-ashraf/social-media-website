<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Post\PostResource;
use App\Http\Resources\UsersListResource;
use App\Http\Responses\SuccessResponse;
use App\Models\Post;
use App\Models\PostTag;
use App\Models\Tag;
use App\Models\User;

class SearchController extends Controller
{
    public function tagSearch ($tag) {
        $tagId = Tag::where('name', $tag)->pluck('id')->first();
        $postsIds = PostTag::where('tag_id', $tagId)->pluck('post_id')->toArray();
        $posts = Post::whereIn('id', $postsIds)->paginate();
        return SuccessResponse::send("Posts by tag {$tag}.", PostResource::collection($posts), meta: [
            'pagination' => [
                'total' => $posts->total(),
                'current_page' => $posts->currentPage(),
                'per_page' => $posts->perPage(),
                'last_page' => $posts->lastPage(),
            ]
        ]);
    }

    public function fullTextSearch ($text) {
        $posts = Post::search($text)->paginate();
        return SuccessResponse::send("Posts search about {$text}.", PostResource::collection($posts), meta: [
            'pagination' => [
                'total' => $posts->total(),
                'current_page' => $posts->currentPage(),
                'per_page' => $posts->perPage(),
                'last_page' => $posts->lastPage(),
            ]
        ]);
    }

    public function userSearch($name) {
        $users = User::where('name', 'like', $name . '%')->paginate();
        return SuccessResponse::send('Users search', UsersListResource::collection($users), meta: [
            'pagination' => [
                'total' => $users->total(),
                'current_page' => $users->currentPage(),
                'per_page' => $users->perPage(),
                'last_page' => $users->lastPage(),
            ]
        ]);
    }
}
