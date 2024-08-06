<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        return view('auth.login');
    }

    public function loginPost(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (
            User::query()
                ->where('email', $data['email'])
                ->exists()
        ) {
            $user = User::query()
                ->where('email', $data['email'])
                ->first();
            if (Hash::check($data['password'], $user->password)) {
                Auth::attempt($data, (bool) $request->input('remember', false));
                return redirect()->route('home')->with('success', 'You have successfully logged in.');
            } else {
                return redirect()
                    ->back()
                    ->withInput($request->except('password'))
                    ->withErrors(['Password salah!']);
            }
        } else {
            return redirect()
                ->back()
                ->withInput($request->except('password'))
                ->withErrors(['Email tidak terdaftar!']);
        }
    }

    public function register(Request $request)
    {
        return view('auth.register');
    }

    public function registerPost(Request $request)
    {
        $data = $request->validate([
            'fullname' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'phone' => 'required',
            'address' => 'required',
        ]);

        try {
            User::query()->create([
                'fullname' => $data['fullname'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'phone' => $data['phone'],
                'address' => $data['address'],
                'role' => RoleEnum::Client,
            ]);
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->withInput($request->except('password'))
                ->withErrors(['Error: ' . $th->getMessage()]);
        }
        return redirect()->route('login')->with('success', 'Register berhasil');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Logout berhasil');
    }
}
