<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'admin_id' => 'required',
            'password' => 'required',
        ]);

        // Query langsung ke tabel admin
        $admin = DB::table('admin')
            ->where('admin_id', $request->admin_id)
            ->where('password', $request->password) // pastikan password belum di-hash
            ->first();

        if ($admin) {
            // Simpan ke session manual
            $request->session()->put('admin', $admin);
            return redirect()->route('dashboard')->with('success', 'Login berhasil!');
        }

        return back()->with('error', 'ID atau Password salah.');
    }
}
