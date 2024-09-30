<?php

namespace App\Http\Services;
use Illuminate\Http\Request;
use App\Http\Repositories\AdminRepositories;

class AdminServices
{
    private $repo;

    public function __construct(AdminRepositories $repo)
    {
        $this->repo = $repo;
    }

    public function index()
    {
        return view('Admin.Dashboard',['user' => $this->repo->index()]);
    }

    public function getusers(Request $request)
    {
        return $this->repo->getusers($request);
    }

    public function destroy(int $id)
    {
        return $this->repo->destroyuser($id);
    }
}



