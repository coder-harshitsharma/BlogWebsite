<?php

namespace App\Http\Repositories;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CommentRepositories
{
    private $model;

    public function __construct(Comment $model)
    {
        $this->model = $model;
    }

    public function storecomments(CommentRequest $request, Post $post)
{
    $validated = $request->validated();

    $comment = $this->model->create([
        'content' => $validated['content'],
        'user_id' => Auth::id(),
        'post_id' => $post->pid,
        'parent_id' => $validated['parent_id'] ?? null,
    ]);

    $is_auth = auth('web')->id() === $comment->user_id || auth('web')->id() === $post->user->id || auth('admin')->user();

    $data = [
        'comment_id' => $comment->id,
        'user_id' => Auth::id(),
        'user_name' => $comment->user->name,
        'user_image' => Storage::url($comment->user->image_path),
        'content' => $comment->content,
        'post_id' => $comment->post_id,
        'time_ago' => $comment->created_at->diffForHumans(),
        'is_auth' => $is_auth,
    ];

    return response()->json($data);
}


    public function destroy(Post $post, $comment)
    {
        if (auth('web')->id() === $comment->user_id || auth('web')->id() === $post->user_id) {
            $comment->delete();
            return response()->json([
                'message' => 'Comment deleted successfully.',
            ]);
        }

        return response()->json([
            'message' => 'Unauthorized action.',
        ]);
    }

    public function destroyReply(Post $post, Comment $comment, Comment $reply)
    {
        if ($reply->parent_id !== $comment->id) {
            return response()->json(['message' => 'Reply does not belong to the comment.']);
        }

        $reply->delete();
        return response()->json(['message' => 'Reply deleted successfully!']);
    }

}
