<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Страница авторизации
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function Index()
    {
        return view('auth');
    }

    /**
     * Авторизация
     *
     * @param Request $request
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function Login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return true;
        }

        return response()->json(['message' => 'Error auth'], 401);
    }

    /**
     * Разлогин
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function Logout(Request $request)
    {
        \auth()->logout();
        $request->session()->regenerate();
        return redirect(route('login'));
    }
}