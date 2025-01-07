<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Post\PostResource;
use App\Http\Responses\SuccessResponse;
use App\Services\HomeService;

class HomeController extends Controller
{
    protected $homeSerive;
    public function __construct(HomeService $homeSerive) {
        $this->homeSerive = $homeSerive;
    }

    public function feed() {
        $posts = $this->homeSerive->getUserFeed();
        return SuccessResponse::send('Posts retrieved successfully.', PostResource::collection($posts), meta: [
            'pagination' => [
                'total' => $posts->total(),
                'current_page' => $posts->currentPage(),
                'per_page' => $posts->perPage(),
                'last_page' => $posts->lastPage(),
            ]
        ]);
    }
}
