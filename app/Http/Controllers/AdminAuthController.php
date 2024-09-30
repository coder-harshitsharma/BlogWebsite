<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\AdminAuthServices;

class AdminAuthController extends Controller
{
    private $service;

    public function __construct(AdminAuthServices $service){
        $this->service = $service;
    }
    public function adminloginmatch(Request $request)
    {

        return $this->service->adminloginmatch($request);
    }

    public function adminchecklogin()
    {
        return $this->service->adminchecklogin();
    }

    public function adminlogout()
    {
        return $this->service->adminlogout();
    }
}
