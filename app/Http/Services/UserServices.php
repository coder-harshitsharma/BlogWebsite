<?php
namespace App\Http\Services;

use Exception;
use Illuminate\View\View;
use App\Http\Requests\UserRequest;
use Illuminate\Http\RedirectResponse;
use App\Http\Repositories\UserRepositories;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserServices
{
    private $repo;
    public function __construct(UserRepositories $repo)
    {
        $this->repo = $repo;
    }

    public function index()
    {
        return view('Users.Userspage', ['users' => $this->repo->getusers()]);
    }

    public function create()
    {
        return redirect()->route('register');
    }

    public function store(UserRequest $request)
    {
        try {
            $result = $this->repo->RegisterUser($request);
            if ($result) {
                return sendSuccessResponse(config('responseMessage.user.create'), route('login'));
            }
            return sendErrResponse(config('responseMessage.user.failed'));
        } catch (Exception $e) {
            return sendErrResponse($e->getMessage());
        }
    }

    public function show(int $id)
    {
        $user = $this->repo->getUser($id);
        return view('Users.Showuser', ['user' => $user, 'posts' => $user->posts]);
    }


    public function edit(int $id)
    {
        $user = $this->repo->edituser($id);
        return view('Auth.Register', ['user' => $user]);
    }


    public function update(UserRequest $request, int $id)
    {
        try {
            $result = $this->repo->updatepost($request, $id);
            if ($result) {
                return sendSuccessResponse(config('responseMessage.user.update'), route('user.profile'));
            }
            return sendErrResponse(config('responseMessage.user.failed'));
        } catch (Exception $e) {
            return sendErrResponse($e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        //
    }

}
