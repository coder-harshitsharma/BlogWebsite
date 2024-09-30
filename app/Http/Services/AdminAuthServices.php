<?php
namespace App\Http\Services;
use Illuminate\Http\Request;

class AdminAuthServices
{
    public function adminloginmatch(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (auth('admin')->attempt($credentials)) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function adminchecklogin()
    {
        if (auth('admin')->check()) {
            return redirect()->route('dashboard');
        } else {
            return redirect()->route('login');
        }
    }

    public function adminlogout()
    {
        auth('admin')->logout();
        return redirect()->route('login');
    }
}
