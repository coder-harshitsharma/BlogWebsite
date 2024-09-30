<?php

use App\Utils\ResponseCode;
use Illuminate\View\View;

function sendSuccessResponse(string $message, string|null|View $redirect)
{
    return response()->json([
        "status" => true,
        "message" => $message,
        "redirect" => $redirect
    ],ResponseCode::HTTP_SUCCESS);
}

function sendErrResponse(string $message)
{
    return response()->json([
        "status" => false,
        'message' => $message
    ],ResponseCode::HTTP_FAILED);
}
