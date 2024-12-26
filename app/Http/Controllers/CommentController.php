<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Http\Resources\Comment\CommentResource;
use App\Http\Resources\Post\PostDetailsResource;
use App\Http\Responses\SuccessResponse;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Validation\UnauthorizedException;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request, Post $post) {
        $post->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->validated()['content'],
            'parent_id' => $request->validated()['parent_id'] ?? null,
        ]);
        return SuccessResponse::send('Comment added', PostDetailsResource::make($post));
    }

    public function update (UpdateCommentRequest $request, Comment $comment) {
        $comment->update($request->validated());
        return SuccessResponse::send('Comment updated', CommentResource::make($comment));
    }

    public function destroy (Comment $comment) {
        if($comment->user_id != auth()->id()) {
            throw new UnauthorizedException;
        }
        $comment->delete();
        return SuccessResponse::send('Comment deleted');
    }
}
