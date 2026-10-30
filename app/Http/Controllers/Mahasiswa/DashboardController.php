<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard mahasiswa.
     */
    public function index(): View
    {
        return view('mahasiswa.dashboard');
    }
}
