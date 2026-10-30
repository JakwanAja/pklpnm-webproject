<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard Dosen Pembimbing.
     */
    public function index(): View
    {
        return view('dosen.dashboard');
    }
}
