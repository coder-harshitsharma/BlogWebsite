<?php

namespace App\Http\Controllers;

use App\Http\Services\LikeServices;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    private $service;

    public function __construct(LikeServices $service){
        $this->service = $service;
    }

    public function toggleLike(Post $post)
    {
        $user = Auth::user();
        return $this->service->toggleLike($post,$user);
    }
}
