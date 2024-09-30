<?php

namespace App\Http\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;

class GoogleAuthRepositories{

    private $model;
    public function __construct(User $model){
        $this->model = $model;
    }

    public function getcallback(): RedirectResponse
    {
        try {
            $google_user = Socialite::driver('google')->user();

            $user = $this->model->where('google_id', $google_user->getId())->first();

            if (!$user) {
                $user = $this->model->where('email', $google_user->getEmail())->first();

                if ($user) {
                    $user->update(['google_id' => $google_user->getId()]);

                    Auth::login($user);
                } else {
                    $new_user = $this->model->create([
                        'name' => $google_user->getName(),
                        'email' => $google_user->getEmail(),
                        'google_id' => $google_user->getId()
                    ]);

                    Auth::login($new_user);
                }
            } else {
                Auth::login($user);
            }

            return redirect()->route('homepictures');
        } catch (\Throwable $th) {
            dd('Something went wrong: ' . $th->getMessage());
        }
    }
}



