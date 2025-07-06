<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function ruangan()
    {
        return view('admin.ruangan'); // sesuaikan jika nama file blade-nya beda
    }

    public function jadwal()
    {
        return view('admin.jadwal'); // sesuaikan jika nama file blade-nya beda
    }
}
