<?php
namespace App\Http\Services;
use Illuminate\Http\Request;


class AuthServices {

    public function loginmatch(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (auth('web')->attempt($credentials)) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function checklogin()
    {
        if (auth('web')->check()) {
            return redirect()->route('homepictures');
        } else {
            return redirect()->route('login');
        }
    }

    public function logout()
    {
        auth('admin')->logout();
        auth('web')->logout();
        return redirect()->route('login');
    }
}
