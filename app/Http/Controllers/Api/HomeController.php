<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Post\PostResource;
use App\Http\Responses\SuccessResponse;
use App\Services\HomeService;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    protected $homeSerivce;
    public function __construct(HomeService $homeService){
        $this->homeService = $homeService;
    }

    public function feed() : JsonResponse{
        $posts = $this->homeService->getUserFeed();
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
