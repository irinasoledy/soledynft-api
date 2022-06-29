<?php

namespace App\Http\Controllers\AdminAuth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomAuthController extends Controller
{
    public function login(Request $request)
    {
        if ($request->isMethod('post'))
            return $this->checkLogin();

        if (Auth::user()) {
            return redirect()->route('back');
        }

        return view('admin.auth.login', get_defined_vars());
    }

    public function checkLogin(Request $request)
    {
        Validator::make($request->all(), [
            'login' => 'required|min:3',
            'password' => 'required|min:4',
        ]);

        if (Auth::attempt(array('login' => $request->get('login'), 'password' => $request->get('password')))) {
            return redirect()->route('back');
        }
        return redirect()->back();
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
