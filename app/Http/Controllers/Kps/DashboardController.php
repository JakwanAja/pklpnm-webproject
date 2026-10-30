<?php

namespace App\Http\Controllers\Kps;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard KPS.
     */
    public function index(): View
    {
        return view('kps.dashboard');
    }
}
