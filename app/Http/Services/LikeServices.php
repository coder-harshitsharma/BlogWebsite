<?php

namespace App\Http\Services;

use App\Models\Post;

class LikeServices {

    public function toggleLike(Post $post,$user)
    {
        if ($post->likedByUsers()->where('user_id', $user->id)->exists()) {
            $post->likedByUsers()->detach($user->id);
            $liked = false;
        } else {
            $post->likedByUsers()->attach($user->id);
            $liked = true;
        }

        return [
            'success' => true,
            'likes_count' => $post->likedByUsers()->count(),
            'liked' => $liked
        ];
    }
}



