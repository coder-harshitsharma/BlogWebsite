<?php

namespace App\Http\Controllers;

use App\Http\Services\CommentLikeServices;
use App\Models\Comment;

class CommentLikeController extends Controller
{

    private $service;

    public function __construct(CommentLikeServices $service){
        $this->service = $service;
    }

    public function toggleLike(Comment $comment)
    {
        return $this->service->toggleLike($comment);
    }
}
