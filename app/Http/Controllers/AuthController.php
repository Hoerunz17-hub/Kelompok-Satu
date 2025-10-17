<?php

namespace App\Http\Controllers;

use App\Models\Usertiga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
  // Tampilkan form login
    // Tampilkan halaman login
    public function showLoginForm()
    {
        return view('pages.auth.Login.index');
    }

    // Proses login
   public function login(Request $request)
{

    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = Usertiga::where('email', $request->email)->first();

    if (!$user) {
        return back()->withErrors(['email' => 'Email tidak ditemukan!']);
    }

    if ($user->role !== 'admin') {
        return back()->withErrors(['email' => 'Hanya admin yang bisa login!']);
    }

    if (!Hash::check($request->password, $user->password)) {
        return back()->withErrors(['password' => 'Password salah!']);
    }

    session([
        'login' => true,
        'user_id' => $user->id,
        'user_name' => $user->name,
        'user_role' => $user->role,
    ]);

    // ✅ ubah redirect-nya ke '/'
    return redirect('/')->with('success', 'Selamat datang, ' . $user->name . '!');
}

    // Logout
    public function logout()
    {
        session()->flush();
        return redirect('/login')->with('success', 'Berhasil logout!');
    }
}
