<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Validation\UnauthorizedException;

class CommentService
{
    public function createComment(Post $post, $content, $parent_id) {
        $post->comments()->create([
            'user_id' => auth()->id(),
            'content' => $content,
            'parent_id' => $parent_id,
        ]);
    }

    public function deleteComment(Comment $comment) {
        if($comment->user_id != auth()->id()
            && $comment->post->user_id != auth()->id()
        ) {
            throw new UnauthorizedException;
        }
        $comment->delete();
    }
}
