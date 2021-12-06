<?php

namespace App\Http\Controllers;

use App\Models\PasienPpi;
use App\Models\PasienPpiDetail;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $total_ppi = PasienPpiDetail::count();
        $total_operasi = PasienPpiDetail::where('is_operasi', 1)->count();
        $chartCtrl = new ChartController;
        // $chartHais = $chartCtrl->hais_chart();
        // $chartJenisOperasi = $chartCtrl->jenis_operasi_chart();

        return view('dashboard', compact(
            'total_ppi',
            'total_operasi'
        ));
    }

    public function statistik(Request $request)
    {
        // dd($request->all());
        $total_ppi = PasienPpiDetail::count();
        $total_operasi = PasienPpiDetail::where('is_operasi', 1)->count();

        $chartCtrl = new ChartController;
        $chartCapaian = $chartCtrl->capaian($request->tahun);

        // dd($chartCapaian);
        return view('statistik.capaian', compact(
            'chartCapaian',
            'total_ppi',
            'total_operasi'
        ));
    }
}
