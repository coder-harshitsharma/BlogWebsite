<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportUserRequest;
use App\Http\Services\ExcelServices;

class ExcelController extends Controller
{

    private $service;

    public function __construct(ExcelServices $service){
        $this->service = $service;
    }

    public function import(ImportUserRequest $request)
    {
        return $this->service->import($request);
    }

}
