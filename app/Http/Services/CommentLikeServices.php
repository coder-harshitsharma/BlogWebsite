<?php

namespace App\Http\Services;

use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentLikeServices{

    public function toggleLike(Comment $comment)
    {
        $user = auth('web')->user();

        if ($comment->likedByUsers()->where('user_id', $user->id)->exists()) {
            $comment->likedByUsers()->detach($user->id);
            $liked = false;
        } else {
            $comment->likedByUsers()->attach($user->id);
            $liked = true;
        }

        return response()->json([
            'success' => true,
            'likes_count' => $comment->likedByUsers()->count(),
            'liked' => $liked,
        ]);
    }
}

