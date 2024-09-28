<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Storage;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $fields = $request->validate([
            'avatar' => ['file', 'nullable', 'max:600'],
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed']
        ]);

        if ($request->hasFile('avatar')) {

            $fields['avatar'] = Storage::disk('public')->put('avatars', $request->avatar);

        }

        $user = User::create($fields);

        Auth::login($user);

        return redirect()->route('home')->with('status', 'Welcome to Laravel Ineria Vue App');

    }

    public function login(Request $request)
    {

        $fields = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required']
        ]);


        // Verifica as credenciais do usuário
        if (Auth::attempt($fields, $request->remember)) {

            $request->session()->regenerate();

            return redirect()->intended('/home')->with('status', 'Welcome to Laravel Ineria Vue App');
            ;
        }

        return back()->withErrors([
            'email' => 'Erro no email ou senha fornecidos'
        ])->onlyInput('email');

    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
