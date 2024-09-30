<?php
namespace App\Http\Repositories;

use App\Models\Post;
use App\Http\Requests\PostRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class PostRepositories
{
    private $model;

    public function __construct(Post $model)
    {
        $this->model = $model;
    }

    public function getposts()
    {
        return $this->model->with('user')->get();
    }

    public function singleshow(int $id)
    {
        return $this->model->find($id);
    }

    public function editpost(int $id)
    {
        return $this->model->find($id);
    }

    public function createblog(PostRequest $request)
    {
        $validated = $request->validated();
        $imagePath = $request->hasFile('image') ? $request->file('image')->store('images', 'public') : null;

        $post = $this->model->create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'image_path' => $imagePath,
            'user_id' => Auth::id()
        ]);

        if ($request->has('tags')) {
            $post->tags()->attach($request->tags);
        }

        return $post;
    }


    public function updatepost(PostRequest $request, int $id)
    {
        $post = $this->model->find($id);
        if ($post) {
            $validated = $request->validated();
            $imagePath = $request->hasFile('image') ? $request->file('image')->store('images', 'public') : $post->image_path;

            $post->update([
                'title' => $validated['title'],
                'content' => $validated['content'],
                'image_path' => $imagePath,
            ]);

            if ($request->has('tags')) {
                $post->tags()->sync($request->tags);
            }

            return true;
        }
        return false;
    }

    public function deletepost(int $id)
    {
        $post = $this->model->find($id);
        return $post->delete();
    }

    public function getPostsByTag($tagId)
    {
        return $this->model->whereHas('tags', function ($query) use ($tagId) {
            $query->where('tag_id', $tagId);
        })->get();
    }

    public function gethomepage(): Collection{
        return $this->model->with('user')->get();
    }

}
