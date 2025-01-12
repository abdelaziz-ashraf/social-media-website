<?php

namespace App\Services;

use App\Models\Badge;

class BadgeService
{
    public function assignPostsBadges()
    {
        $user = auth()->user();
        $numberOfPost = $user->posts()->count();
        if ($numberOfPost < 10) {
            return;
        }
        $log10NumberOfPosts = log10($numberOfPost);

        if(is_int($log10NumberOfPosts)  || floor($log10NumberOfPosts) == $log10NumberOfPosts)  {
            $badge = Badge::firstOrCreate(
                ['name' => "$numberOfPost Posts Badge"],
                ['name' => "$numberOfPost Posts Badge"]
            );
            if ($badge && !$user->badges->contains($badge)) {
                $user->badges()->attach($badge);
            }
        }
    }
}
