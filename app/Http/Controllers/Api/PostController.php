<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Http\Resources\Post\PostDetailsResource;
use App\Http\Responses\SuccessResponse;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function store(StorePostRequest $request) : JsonResponse{
        $post = $this->postService->createPost($request->validated());
        return SuccessResponse::send('Post created successfully!', PostDetailsResource::make($post));
    }
    public function show(Post $post) : JsonResponse{
        return SuccessResponse::send('Post retrieved successfully!', PostDetailsResource::make($post));
    }
    public function update(UpdatePostRequest $request, Post $post) : JsonResponse {
        $post = $this->postService->updatePost($post, $request->validated());
        return SuccessResponse::send('Post updated successfully!', PostDetailsResource::make($post));
    }
    public function destroy(Post $post) : JsonResponse{
        return $this->postService->deletePost($post);
    }
    public function toggleLike(Post $post) : JsonResponse {
        return $this->postService->toggleLikePost($post, auth()->user());
    }
}
