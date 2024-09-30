<?php
namespace App\Http\Controllers;

use App\Http\Services\AuthServices;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private $service;

    public function __construct(AuthServices $service){
        $this->service = $service;
    }
    public function loginmatch(Request $request)
    {
        return $this->service->loginmatch($request);
    }

    public function checklogin()
    {
        return $this->service->checklogin();
    }

    public function logout()
    {
        return $this->service->logout();
    }
}
