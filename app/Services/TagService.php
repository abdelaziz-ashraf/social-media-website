<?php

namespace App\Services;

use App\Models\Post;
use App\Models\PostTag;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;

class TagService
{

    public function extractTags (Post $post) {
        preg_match_all('/#(\w+)/', $post['content'], $matches);
        $tags = $matches[1];

        foreach ($tags as $tagName) {
            DB::transaction(function () use ($post, $tagName) {
                $tag = Tag::firstOrCreate(['name' => $tagName]);
                PostTag::firstOrCreate([
                    'post_id' => $post->id,
                    'tag_id' => $tag->id,
                ]);
            });
        }
    }

}
