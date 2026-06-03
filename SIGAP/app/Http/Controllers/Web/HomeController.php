<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Report;

class HomeController extends Controller
{
    /**
     * GET /
     * Landing page dengan statistik ringkasan.
     */
    public function index()
    {
        $stats = [
            'total_anggaran'  => Report::count(),
            'proyek_aktif'    => Report::whereIn('status', ['diverifikasi', 'ditindaklanjuti'])->count(),
            'proyek_selesai'  => Report::where('status', 'selesai')->count(),
            'total_laporan'   => Report::where('status', '!=', 'ditolak')->count(),
        ];

        return view('home', compact('stats'));
    }
}
