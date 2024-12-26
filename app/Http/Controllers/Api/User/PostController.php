<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Http\Resources\Post\PostDetailsResource;
use App\Http\Resources\Post\PostResource;
use App\Http\Responses\SuccessResponse;
use App\Models\Post;
use App\Notifications\LikePostNotification;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;

class PostController extends Controller
{
    public function index() {
        $followingIds = auth()->user()->followings()->pluck('following_id');
        $posts = Post::whereIn('user_id', $followingIds)
            ->latest()
            ->paginate();
        // todo: when create groups => get posts from my groups too
        return SuccessResponse::send('Posts retrieved successfully.', PostResource::collection($posts), meta: [
            'pagination' => [
                'total' => $posts->total(),
                'current_page' => $posts->currentPage(),
                'per_page' => $posts->perPage(),
                'last_page' => $posts->lastPage(),
            ]
        ]);
    }
    public function store(StorePostRequest $request) {
        $data = $request->validated();
        $post = post::create([
            'user_id' => auth()->id(),
            'group_id' => null,
            'content' => $data['content'],
        ]);
        return SuccessResponse::send('Post created successfully!', PostDetailsResource::make($post));
    }
    public function show(Post $post) {
        return SuccessResponse::send('Post retrieved successfully!', PostDetailsResource::make($post));
    }
    public function update(UpdatePostRequest $request, Post $post) {
        $post->update([
            'content' => $request->get('content')
        ]);
        return SuccessResponse::send('Post updated successfully!', PostDetailsResource::make($post));
    }
    public function destroy(Post $post) {
        // todo: when create group: admin can delete posts (Notify the owner when a post is deleted by the admin)
        if($post->user_id != auth()->id()) {
            throw new UnauthorizedException;
        }
        $post->delete();
        return SuccessResponse::send('Post deleted successfully!');
    }
    public function toggleLike(Post $post) {
        $user = auth()->user();
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
