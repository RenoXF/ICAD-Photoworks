<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::query()
            ->latest();

        if (auth()->user()?->role == \App\Enums\RoleEnum::Admin) {
            $users->whereIn('role', [
                \App\Enums\RoleEnum::Admin,
                \App\Enums\RoleEnum::Client,
            ]);
        }

        return view('user.index', [
            'users' => $users->paginate(20),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'fullname' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
            'address' => 'required',
            'role' => 'nullable|in:' . implode(',', \App\Enums\RoleEnum::values()),
            'password' => 'required|min:8|confirmed'
        ]);

        if (empty($data['role'])) {
            $data['role'] = \App\Enums\RoleEnum::Client;
        } else {
            $data['role'] = \App\Enums\RoleEnum::from($data['role']);
        }

        try {
            User::create($data);
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->withErrors([
                    'user' => $th->getMessage(),
                ]);
        }

        return redirect()->route('user.index')->with('success', 'Data berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'fullname' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($id)
            ],
            'phone' => 'required',
            'address' => 'required',
            'role' => 'nullable|in:' . implode(',', \App\Enums\RoleEnum::values()),
            'password' => 'nullable|min:8|confirmed'
        ]);

        $user = User::find($id);

        if (!$user) {
            return redirect()
                ->route('user.index')
                ->withErrors([
                    'user' => 'Data pengguna tidak ditemukan',
                ]);
        }

        if (empty($data['role'])) {
            $data['role'] = \App\Enums\RoleEnum::Client;
        } else {
            $data['role'] = \App\Enums\RoleEnum::from($data['role']);
        }

        if (empty($data['password'])) {
            unset($data['password']);
        }

        try {
            $user->update($data);
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->withErrors([
                    'user' => $th->getMessage(),
                ]);
        }

        return redirect()->route('user.index')->with('success', 'Data berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()
                ->route('user.index')
                ->withErrors([
                    'user' => 'Data pengguna tidak ditemukan',
                ]);
        }

        try {
            $user->delete();
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->withErrors([
                    'user' => $th->getMessage(),
                ]);
        }

        return redirect()->route('user.index')->with('success', 'Data berhasil dihapus');
    }
}
