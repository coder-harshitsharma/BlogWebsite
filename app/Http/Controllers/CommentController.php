<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Http\Services\CommentServices;
use App\Models\Comment;
use App\Models\Post;

class CommentController extends Controller
{
    private $service;
    public function __construct(CommentServices $service)
    {
        $this->service = $service;
    }

    public function store(CommentRequest $request, Post $post)
    {
        return $this->service->store($request, $post);
    }

    public function destroy(Post $post, Comment $comment)
    {
        return $this->service->destroy($post, $comment);
    }

    public function destroyReply(Post $post, Comment $comment, Comment $reply)
    {
        return $this->service->destroyreply($post,$comment,$reply);
    }

}
