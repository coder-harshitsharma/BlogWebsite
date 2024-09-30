<?php
namespace App\Http\Services;

use Exception;
use App\Models\Tag;
use App\Http\Requests\PostRequest;
use Illuminate\Contracts\View\View;
use App\Http\Repositories\PostRepositories;

class PostServices
{
    private $repo;

    public function __construct(PostRepositories $repo)
    {
        $this->repo = $repo;
    }

    public function index()
    {
        return view('Posts.Posts', ['tags' => Tag::all(), 'posts' => $this->repo->getposts()]);
    }

    public function create()
    {
        $tags = Tag::all();
        return view('Posts.CreatePosts', ['tags' => $tags]);
    }

    public function show(int $id)
    {
        return view('Posts.Show', ['post' => $this->repo->singleshow($id)]);
    }

    public function store(PostRequest $request)
    {
        try {
            $result = $this->repo->createblog($request);
            if ($result) {
                return sendSuccessResponse(config('responseMessage.post.create'), route('newposts.index'));
            }
            return sendErrResponse(config('responseMessage.post.failed'));
        } catch (Exception $e) {
            return sendErrResponse($e->getMessage());
        }
    }

    public function edit(int $id)
    {
        $post = $this->repo->editpost($id);
        return view('Posts.CreatePosts', ['post' => $post]);
    }

    public function update(PostRequest $request, int $id)
    {
        try {
            $result = $this->repo->updatepost($request, $id);
            if ($result) {
                return sendSuccessResponse(config('responseMessage.post.update'), route('newposts.index'));
            }
            return sendErrResponse(config('responseMessage.post.failed'));
        } catch (Exception $e) {
            return sendErrResponse($e->getMessage());
        }
    }

    public function destroy(int $id)
    {
        try {
            $result = $this->repo->deletepost($id);
            if ($result) {
                return sendSuccessResponse(config('responseMessage.post.delete'), route('newposts.index'));
            }
            return sendErrResponse(config('responseMessage.post.failed'));
        } catch (Exception $e) {
            return sendErrResponse($e->getMessage());
        }
    }

    public function filterposts($tagId){
        $posts = $this->repo->getPostsByTag($tagId);
        return $posts;
    }

    public function homepage(): View{
        return view('masterpage',['posts' => $this->repo->gethomepage()]);
    }
}
