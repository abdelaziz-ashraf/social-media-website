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
use App\Services\SearchServices;

class SearchController extends Controller
{
    protected $searchService;
    public function __construct(SearchServices $searchService) {
        $this->searchService = $searchService;
    }

    public function tagSearch ($tag) {
        $posts = $this->searchService->searchByTag($tag);
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
        $posts = $this->searchService->fullTextPostSearch($text);
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
        $users = $this->searchService->userSearchByName($name);
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
