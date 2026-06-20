<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'nip'                   => ['nullable', 'string', 'max:50', 'unique:users,nip'],
            'department'            => ['nullable', 'string', 'max:100'],
            'position'              => ['nullable', 'string', 'max:100'],
            'email'                 => ['required', 'email', 'unique:users,email'],
            'password'              => ['required', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name'       => $request->name,
            'nip'        => $request->nip,
            'department' => $request->department,
            'position'   => $request->position,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'role'       => 'karyawan',   // default role untuk pendaftar baru
            'is_active'  => true,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('success', 'Akun berhasil dibuat. Selamat datang, ' . $user->name . '!');
    }
}
