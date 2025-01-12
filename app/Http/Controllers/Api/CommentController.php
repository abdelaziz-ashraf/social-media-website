<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Http\Resources\Comment\CommentResource;
use App\Http\Resources\Post\PostDetailsResource;
use App\Http\Responses\SuccessResponse;
use App\Models\Comment;
use App\Models\Post;
use App\Services\CommentService;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
    protected $commentService;
    public function __construct(CommentService $commentService) {
        $this->commentService = $commentService;
    }

    public function store(StoreCommentRequest $request, Post $post) : JsonResponse {
        $data = $request->validated();
        $this->commentService->createComment($post, $data['content'], $data['parent_id'] ?? null);
        return SuccessResponse::send('Comment added', PostDetailsResource::make($post));
    }

    public function update (UpdateCommentRequest $request, Comment $comment) : JsonResponse{
        $comment->update($request->validated());
        return SuccessResponse::send('Comment updated', CommentResource::make($comment));
    }

    public function destroy (Comment $comment) : JsonResponse{
       $this->commentService->deleteComment($comment);
        return SuccessResponse::send('Comment deleted');
    }
}
