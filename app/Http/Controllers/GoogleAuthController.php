<?php

namespace App\Http\Controllers;

use App\Http\Services\GoogleAuthServices;
use Symfony\Component\HttpFoundation\RedirectResponse;

class GoogleAuthController extends Controller
{
    private $service;
    public function __construct(GoogleAuthServices $service){
        $this->service = $service;
    }
    public function redirect(): RedirectResponse
    {
        return $this->service->redirect();
    }

    public function callback(): RedirectResponse
    {
        return $this->service->callback();
    }
}
