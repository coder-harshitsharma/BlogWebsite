<?php
namespace App\Http\Services;

use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Repositories\GoogleAuthRepositories;

class GoogleAuthServices{
    private $repo;
    public function __construct(GoogleAuthRepositories $repo){
        $this->repo = $repo;
    }

    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(): RedirectResponse
    {
        return $this->repo->getcallback();
    }

}
