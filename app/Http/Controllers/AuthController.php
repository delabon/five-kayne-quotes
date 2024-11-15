<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function login(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        if (!Auth::attempt($request->validated())) {
            return to_route('login')->withErrors([
                'email' => 'Invalid login credentials.'
            ]);
        }

        session()->regenerate();
        session()->put('token', auth()->user()->createToken('auth_token')->plainTextToken);

        return to_route('home')->with([
            'success' => 'You have signed-in successfully.'
        ]);
    }
}
