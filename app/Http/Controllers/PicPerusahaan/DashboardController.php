<?php

namespace App\Http\Controllers\PicPerusahaan;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard PIC Perusahaan.
     */
    public function index(): View
    {
        return view('pic-perusahaan.dashboard');
    }
}
