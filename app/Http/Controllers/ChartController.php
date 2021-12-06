<?php

namespace App\Http\Controllers;

use App\Charts\CapaianChart;
use Illuminate\Http\Request;
use App\Charts\HaisChart;
use App\Charts\JenisOperasiChart;
use App\Models\AlatDigunakan;
use App\Models\Export;
use App\Models\JenisOperasi;
use App\Models\PasienPpiDetail;
use Exception;

class ChartController extends Controller
{
    public function hais_chart()
    {
        $rekap = new RekapController;
        $total = $rekap->total_jenis_infeksi_rs();

        $dataset = array();
        $model = [
            'vap',
            'hap',
            'isk',
            'iadp',
            'ido',
            'pleb',
            'tirah_baring'
        ];
        foreach($model as $data) {
            foreach($total as $key => $item)
            {
                if($data == $key) {
                    array_push($dataset, $item);
                }
            }
        }

        // dd($dataset);
        $labels = [
            'VAP',
            'HAP',
            'ISK',
            'IADP',
            'IDO',
            'PLEB',
            'TIRAH BARING'
        ];

        $chart = new HaisChart;
        $chart->labels($labels);
        $chart->dataset('Grafik HAI\'s', 'bar', $dataset)->color('#5603ad')->backgroundColor('#5603ad');
        // $chart->export(true);

        return $chart;
        // return view('charts.sample_view', compact('chart'));
    }

    public function jenis_operasi_chart()
    {
        $rekap = new RekapController;
        $total = $rekap->total_jenis_operasi();
        $dataset = array();
        $jenis_operasi = JenisOperasi::all();
        $labels = array();
        $dataset = array();
        foreach($jenis_operasi as $item)
        {
            array_push($labels, $item->nama_jenis_operasi);
            foreach($total as $key => $value)
            {
                if($item->nama_jenis_operasi == $key) {
                    array_push($dataset, $value);
                }
            }
        }

        $chart = new JenisOperasiChart;
        
        $chart->title("Tes");
        $chart->labels($labels);
        $chart->dataset('Grafik Jenis Operasi', 'bar', $dataset)
            ->color('#2dce89')
            ->backgroundColor('#2dce89');
        // $chart->export(true);

        return $chart;
    }

    public function capaian($tahun)
    {
        $chart_vap = new CapaianChart;
        $chart_hap = new CapaianChart;
        $chart_isk = new CapaianChart;
        $chart_iadp = new CapaianChart;
        $chart_pleb = new CapaianChart;
        $chart_ido = new CapaianChart;

        $month = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Aug','Sep','Okt','Nov','Des'];

        $label_vap = array();
        $label_hap = array();
        $label_isk = array();
        $label_iadp = array();
        $label_pleb = array();
        $label_ido = array();

        foreach($month as $m)
        {
            $m = $m.' '.$tahun;
            array_push($label_vap, $m);
            array_push($label_hap, $m);
            array_push($label_isk, $m);
            array_push($label_iadp, $m);
            array_push($label_pleb, $m);
            array_push($label_ido, $m);
        }

        $vap = Export::where('jenis_infeksi', 'vap')
                        ->where('tahun', $tahun)
                        ->get();
        $hap = Export::where('jenis_infeksi', 'hap')
                        ->where('tahun', $tahun)
                        ->get();
        $isk = Export::where('jenis_infeksi', 'isk')
                        ->where('tahun', $tahun)
                        ->get();
        $iadp = Export::where('jenis_infeksi', 'iadp')
                        ->where('tahun', $tahun)
                        ->get();
        $pleb = Export::where('jenis_infeksi', 'pleb')
                        ->where('tahun', $tahun)
                        ->get();
        $ido = Export::where('jenis_infeksi', 'ido')
                        ->where('tahun', $tahun)
                        ->get();

        if(count($vap) == 0) {
            $chart_vap = "Not Found";
        } else {
            $dataset_vap = array();
            foreach($vap as $e)
            {
                array_push($dataset_vap, $e->capaian);
            }
            $chart_vap->title("Capaian VAP ".$tahun);
            $chart_vap->labels($label_vap);
            $chart_vap->dataset('Statistik Capaian '.$tahun, 'line', $dataset_vap)
                ->color('#2dce89');
                // ->backgroundColor('#2dce89');
            $chart_vap->export(true);
        }

        if(count($hap) == 0) {
            $chart_hap = "Not Found";
        } else {
            $dataset_hap = array();
            foreach($hap as $e)
            {
                array_push($dataset_hap, $e->capaian);
            }
            $chart_hap->title("Capaian HAP ".$tahun);
            $chart_hap->labels($label_hap);
            $chart_hap->dataset('Statistik Capaian '.$tahun, 'line', $dataset_hap)
                ->color('#2dce89');
                // ->backgroundColor('#2dce89');
            $chart_hap->export(true);
        }

        if(count($isk) == 0) {
            $chart_isk = "Not Found";
        } else {
            $dataset_isk = array();
            foreach($isk as $e)
            {
                array_push($dataset_isk, $e->capaian);
            }
            $chart_isk->title("Capaian ISK ".$tahun);
            $chart_isk->labels($label_isk);
            $chart_isk->dataset('Statistik Capaian '.$tahun, 'line', $dataset_isk)
                ->color('#2dce89');
                // ->backgroundColor('#2dce89');
            $chart_isk->export(true);
        }

        if(count($iadp) == 0) {
            $chart_iadp = "Not Found";
        } else {
            $dataset_iadp = array();
            foreach($iadp as $e)
            {
                array_push($dataset_iadp, $e->capaian);
            }
            $chart_iadp->title("Capaian IADP ".$tahun);
            $chart_iadp->labels($label_iadp);
            $chart_iadp->dataset('Statistik Capaian '.$tahun, 'line', $dataset_iadp)
                ->color('#2dce89');
                // ->backgroundColor('#2dce89');
            $chart_iadp->export(true);
        }

        if(count($pleb) == 0) {
            $chart_pleb = "Not Found";
        } else {
            $dataset_pleb = array();
            foreach($pleb as $e)
            {
                array_push($dataset_pleb, $e->capaian);
            }
            $chart_pleb->title("Capaian PLEB ".$tahun);
            $chart_pleb->labels($label_pleb);
            $chart_pleb->dataset('Statistik Capaian '.$tahun, 'line', $dataset_pleb)
                ->color('#2dce89');
                // ->backgroundColor('#2dce89');
            $chart_pleb->export(true);
        }

        if(count($ido) == 0) {
            $chart_ido = "Not Found";
        } else {
            $dataset_ido = array();
            foreach($ido as $e)
            {
                array_push($dataset_ido, $e->capaian);
            }
            $chart_ido->title("Capaian IDO ".$tahun);
            $chart_ido->labels($label_ido);
            $chart_ido->dataset('Statistik Capaian '.$tahun, 'line', $dataset_ido)
                ->color('#2dce89');
                // ->backgroundColor('#2dce89');
            $chart_ido->export(true);
        }

        return [
            $chart_vap,
            $chart_hap,
            $chart_isk,
            $chart_iadp,
            $chart_pleb,
            $chart_ido
        ];
    }

    public function _capaian($infeksi_rs, $tahun) {
        $arr_data = array();
        $model = [
            'vap' => 0,
            'hap' => 0,
            'isk' => 0,
            'iadp' => 0,
            'pleb' => 0,
            // 'ido' => 0,
        ];

        $pasien_ppi = PasienPpiDetail::whereYear('tgl_sensus',$tahun)->get();
        foreach($pasien_ppi as $ppi) {
            foreach($ppi->jenis_infeksi_rs as $data) {
                // dd($data);
                foreach($model as $iterasi => $value) {
                    if(isset($data[$iterasi])) {
                        if($data[$iterasi] != null) {
                            $model[$iterasi] = $model[$iterasi] + 1;
                        }
                        $arr_data[$iterasi] = $model[$iterasi];
                    }
                }
            }
        }

        return $arr_data;
    }

    public function _alat($alat) {
        $alat = AlatDigunakan::where('nama_alat_digunakan', $alat)->first();
        if($alat == null) {
            return 'error';
        }
        
        return $alat;
    }

    public function _vap($n, $d) {
        try {            
            $capaian = $n / $d * 1000;
            return $capaian;
        } catch (Exception $e) {
            $err = $e->getMessage();
            if($err == "Division by zero") {
                return 0;
            }
            // return $e->getMessage();
        }
    }
}
