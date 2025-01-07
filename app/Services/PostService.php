<?php

namespace App\Services;

use App\Http\Responses\SuccessResponse;
use App\Models\GroupUser;
use App\Models\Post;
use App\Models\PostTag;
use App\Models\Tag;
use App\Notifications\GroupAdminDeletedYourPostNotification;
use App\Notifications\LikePostNotification;
use Illuminate\Validation\UnauthorizedException;

class PostService
{
    public function createPost($data) {
        $post = Post::create([
            'user_id' => auth()->id(),
            'group_id' => $data['group_id'] ?? null,
            'content' => $data['content'],
        ]);
        if(isset($data['tags'])) {
            foreach ($data['tags'] as $tag) {
                $tag = Tag::firstOrCreate(['name' => $tag]);
                PostTag::firstOrCreate([
                    'post_id' => $post->id,
                    'tag_id' => $tag->id,
                ]);
            }
        }
        return $post;
    }

    public function updatePost(Post $post, $data) {
        $post->update([
            'content' => $data['content']
        ]);
        if(isset($data['tags'])) {
            foreach ($data['tags'] as $tag) {
                $tag = Tag::firstOrCreate(['name' => $tag]);
                PostTag::firstOrCreate([
                    'post_id' => $post->id,
                    'tag_id' => $tag->id,
                ]);
            }
        }
        return $post;
    }

    public function deletePost(Post $post) {
        $groupAdmin = null;
        if($post->group_id) {
            $groupAdmin = GroupUser::where('group_id', $post->group_id)
                ->whereIn('role', ['owner', 'admin'])->first()->user;
        }

        if($post->user_id != auth()->id() && $groupAdmin->id != auth()->id()) {
            throw new UnauthorizedException;
        }
        $post->forceDelete();
        if($post->user_id != auth()->id() && $groupAdmin->id == auth()->id()) {
            auth()->user()->notify(new GroupAdminDeletedYourPostNotification($post->group->name));
        }
        return SuccessResponse::send('Post deleted successfully!');
    }

    public function toggleLikePost(Post $post, $user) {
        if($post->likes()->where('user_id', $user->id)->exists()) {
            $post->likes()->where('user_id', $user->id)->first()->delete();
            return SuccessResponse::send('Post unliked successfully!');
        }
        $post->likes()->create([
            'user_id' => $user->id,
        ]);
        $user->notify(new LikePostNotification());
        return SuccessResponse::send('Post liked successfully!');
    }
}
