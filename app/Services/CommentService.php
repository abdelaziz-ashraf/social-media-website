<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Post;
use App\Notifications\CommentNotification;
use Illuminate\Validation\UnauthorizedException;

class CommentService
{
    public function createComment(Post $post, string $content, null|int $parent_id) : void {
        $post->comments()->create([
            'user_id' => auth()->id(),
            'content' => $content,
            'parent_id' => $parent_id,
        ]);
        $post->user->notify(new CommentNotification());
        foreach($post->postNotificationsSubscriptions as $subscription) {
            $subscription->user->notify(new CommentNotification());
        }
    }

    public function deleteComment(Comment $comment) : void {
        if($comment->user_id != auth()->id()
            && $comment->post->user_id != auth()->id()
        ) {
            throw new UnauthorizedException;
        }
        $comment->delete();
    }
}
