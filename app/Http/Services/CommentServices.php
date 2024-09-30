<?php

namespace App\Http\Services;

use App\Models\Post;
use App\Models\Comment;
use App\Http\Requests\CommentRequest;
use App\Http\Repositories\CommentRepositories;

class CommentServices
{
    private $repo;

    public function __construct(CommentRepositories $repo)
    {
        $this->repo = $repo;
    }

    public function store(CommentRequest $request, Post $post)
    {
        return $this->repo->storecomments($request, $post);
    }

    public function destroy(Post $post, $comment)
    {
        return $this->repo->destroy($post, $comment);
    }

    public function destroyReply(Post $post, Comment $comment, Comment $reply)
    {
        return $this->repo->destroyreply($post,$comment,$reply);
    }

}
