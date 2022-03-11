<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
class MahasiswaController extends Controller
{
    //method menampilkan data dari database
    public function index()
    {
        $mahasiswa = Mahasiswa::latest()->paginate(5);

        return view('mahasiswa.index', compact('mahasiswa'));
    }
}
