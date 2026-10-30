<?php

namespace App\Http\Controllers\PicMagang;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard PIC Magang.
     */
    public function index(): View
    {
        return view('pic-magang.dashboard');
    }
}
