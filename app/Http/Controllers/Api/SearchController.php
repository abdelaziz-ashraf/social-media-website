<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Post\PostResource;
use App\Http\Resources\UsersListResource;
use App\Http\Responses\SuccessResponse;
use App\Services\SearchServices;
use Illuminate\Http\Request;

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

    public function userSearch(Request $request) {
        $name = $request->validate([
            'name' => 'required|min:2',
        ])['name'];
        $users = $this->searchService->userSearchByName($name);
        return SuccessResponse::send('Users search', UsersListResource::collection($users));
    }
}
