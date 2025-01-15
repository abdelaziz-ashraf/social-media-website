<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Tag\PopularTagsResource;
use App\Http\Responses\SuccessResponse;
use App\Services\TagService;

class TagController extends Controller
{
    protected $tagSerives;
    public function __construct(TagService $tagSerives) {
        $this->tagSerives = $tagSerives;
    }
    public function popularToday() {
        $popularTags = $this->tagSerives->popularTagsToday();
        return SuccessResponse::send('Popular tags today', PopularTagsResource::collection($popularTags));
    }
}
