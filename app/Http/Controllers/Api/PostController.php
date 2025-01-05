<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Http\Resources\Post\PostDetailsResource;
use App\Http\Resources\Post\PostResource;
use App\Http\Responses\SuccessResponse;
use App\Models\GroupUser;
use App\Models\Post;
use App\Models\PostTag;
use App\Models\Tag;
use App\Notifications\GroupAdminDeletedYourPostNotification;
use App\Notifications\LikePostNotification;
use Illuminate\Validation\UnauthorizedException;

class PostController extends Controller
{
    public function index() {

        $userId = auth()->id();
        $userGroupIds = GroupUser::where('user_id', $userId)
            ->pluck('group_id')->toArray();

        $followerIds = auth()->user()->followers()->pluck('users.id')->toArray();

        $posts = Post::where(function ($query) use ($userGroupIds, $followerIds) {
            $query->whereIn('group_id', $userGroupIds);

            $query->orWhere(function ($subQuery) use ($followerIds, $userGroupIds) {
                $subQuery->whereIn('user_id', $followerIds)
                    ->whereNotIn('group_id', $userGroupIds);
            });
        })->when(request('filter') === 'popular', function ($query) {
            $query->orderBy('number_of_likes', 'desc')
                ->orderBy('number_of_comments', 'desc');
        })->paginate();

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
        $post = Post::create([
            'user_id' => auth()->id(),
            'group_id' => $data['group_id'] ?? null,
            'content' => $data['content'],
        ]);

        foreach ($data['tags'] as $tag) {
            $tag = Tag::firstOrCreate(['name' => $tag]);
            PostTag::firstOrCreate([
               'post_id' => $post->id,
               'tag_id' => $tag->id,
            ]);
        }

        return SuccessResponse::send('Post created successfully!', PostDetailsResource::make($post));
    }
    public function show(Post $post) {
        return SuccessResponse::send('Post retrieved successfully!', PostDetailsResource::make($post));
    }
    public function update(UpdatePostRequest $request, Post $post) {
        $data = $request->validated();
        $post->update([
            'content' => $data['content']
        ]);
        foreach ($data['tags'] as $tag) {
            $tag = Tag::firstOrCreate(['name' => $tag]);
            PostTag::firstOrCreate([
                'post_id' => $post->id,
                'tag_id' => $tag->id,
            ]);        }
        return SuccessResponse::send('Post updated successfully!', PostDetailsResource::make($post));
    }
    public function destroy(Post $post) {
        $groupAdmin = null;
        if((!is_null($post->group_id))) {
            $groupAdmin = GroupUser::where('group_id', $post->group_id)
                ->whereIn('role', ['owner', 'admin'])->first()->user;
        }

        if($post->user_id != auth()->id() && $groupAdmin->id != auth()->id()) {
            throw new UnauthorizedException;
        }
        $post->delete();
        if($post->user_id != auth()->id() && $groupAdmin->id == auth()->id()) {
            auth()->user()->notify(new GroupAdminDeletedYourPostNotification($post->group->name));
        }
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
