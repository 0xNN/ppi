<?php

namespace App\Http\Controllers;

use App\Exports\HapExport;
use App\Exports\IadpExport;
use App\Exports\IdoExport;
use App\Exports\IskExport;
use App\Exports\PlebExport;
use App\Exports\RekapExport;
use App\Exports\RekapPasienExport;
use App\Exports\VapExport;
use App\Models\AlatDigunakan;
use App\Models\Antibiotik;
use App\Models\AsaScore;
use App\Models\Export;
use App\Models\JenisOperasi;
use App\Models\KategoriAntibiotik;
use App\Models\KegiatanSensus;
use App\Models\LamaOperasi;
use App\Models\PasienPpi;
use App\Models\PasienPpiDetail;
use App\Models\RiskScore;
use App\Models\TindakanOperasi;
use Illuminate\Http\Request;
use Exception;
use Maatwebsite\Excel\Facades\Excel;

class RekapController extends Controller
{
    public function index(Request $request)
    {
        $tindakan_operasi = TindakanOperasi::all();
        return view('rekap.admin.index', compact(
            'tindakan_operasi'
        ));
    }

    public function filter_date(Request $request) 
    {
        // dd($pasien_ppi);
        $total_alat_digunakan = $this->total_alat_digunakan($request->tgl_masuk, $request->tgl_keluar);
        $total_kegiatan_sensus = $this->total_kegiatan_sensus($request->tgl_masuk, $request->tgl_keluar);
        $total_jenis_operasi = $this->total_jenis_operasi($request->tgl_masuk, $request->tgl_keluar);
        $total_lama_operasi = $this->total_lama_operasi($request->tgl_masuk, $request->tgl_keluar);
        $total_asa_score = $this->total_asa_score($request->tgl_masuk, $request->tgl_keluar);
        $total_risk_score = $this->total_risk_score($request->tgl_masuk, $request->tgl_keluar);
        $total_jenis_infeksi_rs = $this->total_jenis_infeksi_rs($request->tgl_masuk, $request->tgl_keluar);
        $total_jenis_pendukung_kultur = $this->total_jenis_pendukung_kultur($request->tgl_masuk, $request->tgl_keluar);
        $total_antibiotik = $this->total_antibiotik($request->tgl_masuk, $request->tgl_keluar);
        // dd($total_jenis_infeksi_rs);

        $tgl_masuk = $request->tgl_masuk;
        $tgl_keluar = $request->tgl_keluar;
        return view('rekap.admin.filter',compact(
            'total_alat_digunakan',
            'total_kegiatan_sensus',
            'total_jenis_operasi',
            'total_lama_operasi',
            'total_asa_score',
            'total_risk_score',
            'total_jenis_infeksi_rs',
            'total_jenis_pendukung_kultur',
            'total_antibiotik',
            'tgl_masuk',
            'tgl_keluar'
        ));
    }

    public function filter_tahun(Request $request)
    {
        // dd($request->all());
        if($request->infeksi_rs == "pilih") {
            return back()->with('error', 'Infeksi RS belum dipilih');
        }

        // dd($request->tahun_filter);
        $infeksi_rs = $this->_capaian($request->infeksi_rs, $request->tahun_filter);

        $month = ['01','02','03','04','05','06','07','08','09','10','11','12'];

        if($request->infeksi_rs == 'vap') {
            $alat_vap = $this->_alat('ETT Ventilator');
            if($alat_vap == 'error') {
                return back()->with('error', 'Pastikan penamaan alat untuk VAP adalah "ETT Ventilator" di Data Master');
            }
    
            $arr_denumerator_vap = array();
            $arr_numerator_vap = array();
    
            foreach($month as $m) {
                $denumerator_vap = 0;
                $numerator_vap = 0;
                $pasien_ppi = PasienPpiDetail::whereYear('tgl_sensus', $request->tahun_filter)
                                            ->whereMonth('tgl_sensus', $m)
                                            ->get();
                foreach($pasien_ppi as $ppi) {
                    foreach($ppi->alat_digunakan_id as $data) {
                        if($data == $alat_vap->id) {
                            $denumerator_vap = $denumerator_vap + 1;
                        }
                    }
                }
    
                foreach($pasien_ppi as $ppi) {
                    foreach($ppi->jenis_infeksi_rs as $data) {
                        if(array_key_exists("vap", $data)) {
                            if($data["vap"] != null) {
                                $numerator_vap = $numerator_vap + 1;
                            }
                        }
                    }
                }
                $mon = date("F", mktime(0, 0, 0, $m, 1));
                $arr_denumerator_vap[$mon] = $denumerator_vap;
                $arr_numerator_vap[$mon] = $numerator_vap;
            }
            // $_capaian_vap = $this->_vap($infeksi_rs['vap'], $denumerator_vap);
            // dd($arr_numerator_vap);
            $data = array();
            // dd($arr_denumerator_vap["November"]);
            foreach($arr_denumerator_vap as $key => $value) {
                $data["bulan"] = $key;
                $data["numerator"] = $arr_numerator_vap[$key];
                $data["denumerator"] = $value;
                $data["capaian"] = $this->_vap($arr_numerator_vap[$key], $arr_denumerator_vap[$key]);
                $data["target"] = 5.80;
                $data["rata_rata"] = 2.32;
                $data["analisis"] = null;
                $data["tindak_lanjut"] = null;
                $data["tahun"] = $request->tahun_filter;
                $data["jenis_infeksi"] = $request->infeksi_rs;
                
                Export::updateOrCreate(
                    [
                        'tahun' => $request->tahun_filter,
                        'jenis_infeksi' => $request->infeksi_rs,
                        'bulan' => $key
                    ],$data
                );
            }
    
            return Excel::download(new VapExport($request->tahun_filter), 'vap.xlsx');
        }

        if($request->infeksi_rs == 'hap') {
    
            $arr_denumerator_hap = array();
            $arr_numerator_hap = array();
    
            foreach($month as $m) {
                $denumerator_hap = 0;
                $numerator_hap = 0;
                $pasien_ppi = PasienPpiDetail::whereYear('tgl_sensus', $request->tahun_filter)
                                            ->whereMonth('tgl_sensus', $m)
                                            ->get();
                foreach($pasien_ppi as $ppi) {
                    foreach($ppi->jenis_infeksi_rs as $data) {
                        if(array_key_exists("tirah_baring", $data)) {
                            if($data["tirah_baring"] != null) {
                                $denumerator_hap = $denumerator_hap + 1;
                            }
                        }
                    }
                }
    
                foreach($pasien_ppi as $ppi) {
                    foreach($ppi->jenis_infeksi_rs as $data) {
                        if(array_key_exists("hap", $data)) {
                            if($data["hap"] != null) {
                                $numerator_hap = $numerator_hap + 1;
                            }
                        }
                    }
                }
                $mon = date("F", mktime(0, 0, 0, $m, 1));
                $arr_denumerator_hap[$mon] = $denumerator_hap;
                $arr_numerator_hap[$mon] = $numerator_hap;
            }
            // $_capaian_hap = $this->_hap($infeksi_rs['hap'], $denumerator_hap);
            // dd($arr_numerator_hap);
            $data = array();
            // dd($arr_denumerator_hap["November"]);
            foreach($arr_denumerator_hap as $key => $value) {
                $data["bulan"] = $key;
                $data["numerator"] = $arr_numerator_hap[$key];
                $data["denumerator"] = $value;
                $data["capaian"] = $this->_hap($arr_numerator_hap[$key], $arr_denumerator_hap[$key]);
                $data["target"] = 1;
                $data["rata_rata"] = 2.32;
                $data["analisis"] = null;
                $data["tindak_lanjut"] = null;
                $data["tahun"] = $request->tahun_filter;
                $data["jenis_infeksi"] = $request->infeksi_rs;
                
                Export::updateOrCreate(
                    [
                        'tahun' => $request->tahun_filter,
                        'jenis_infeksi' => $request->infeksi_rs,
                        'bulan' => $key
                    ],$data
                );
            }
    
            return Excel::download(new HapExport($request->tahun_filter), 'hap.xlsx');
        }

        if($request->infeksi_rs == 'isk') {
            $alat_isk = $this->_alat('UC');
            if($alat_isk == 'error') {
                return back()->with('error', 'Pastikan penamaan alat untuk ISK adalah "UC" di Data Master');
            }

            $arr_denumerator_isk = array();
            $arr_numerator_isk = array();
    
            foreach($month as $m) {
                $denumerator_isk = 0;
                $numerator_isk = 0;
                $pasien_ppi = PasienPpiDetail::whereYear('tgl_sensus', $request->tahun_filter)
                                            ->whereMonth('tgl_sensus', $m)
                                            ->get();
                foreach($pasien_ppi as $ppi) {
                    foreach($ppi->alat_digunakan_id as $data) {
                        if($data == $alat_isk->id) {
                            $denumerator_isk = $denumerator_isk + 1;
                        }
                    }
                }
    
                foreach($pasien_ppi as $ppi) {
                    foreach($ppi->jenis_infeksi_rs as $data) {
                        if(array_key_exists("isk", $data)) {
                            if($data["isk"] != null) {
                                $numerator_isk = $numerator_isk + 1;
                            }
                        }
                    }
                }
                $mon = date("F", mktime(0, 0, 0, $m, 1));
                $arr_denumerator_isk[$mon] = $denumerator_isk;
                $arr_numerator_isk[$mon] = $numerator_isk;
            }
            // $_capaian_isk = $this->_isk($infeksi_rs['isk'], $denumerator_isk);
            // dd($arr_numerator_isk);
            $data = array();
            // dd($arr_denumerator_isk["November"]);
            foreach($arr_denumerator_isk as $key => $value) {
                $data["bulan"] = $key;
                $data["numerator"] = $arr_numerator_isk[$key];
                $data["denumerator"] = $value;
                $data["capaian"] = $this->_isk($arr_numerator_isk[$key], $arr_denumerator_isk[$key]);
                $data["target"] = 4.7;
                $data["rata_rata"] = 2.32;
                $data["analisis"] = null;
                $data["tindak_lanjut"] = null;
                $data["tahun"] = $request->tahun_filter;
                $data["jenis_infeksi"] = $request->infeksi_rs;
                
                Export::updateOrCreate(
                    [
                        'tahun' => $request->tahun_filter,
                        'jenis_infeksi' => $request->infeksi_rs,
                        'bulan' => $key
                    ],$data
                );
            }

            return Excel::download(new IskExport($request->tahun_filter), 'isk.xlsx');
        }

        if($request->infeksi_rs == 'iadp') {
            $alat_iadp = $this->_alat('CVC');
            if($alat_iadp == 'error') {
                return back()->with('error', 'Pastikan penamaan alat untuk IADP adalah "CVC" di Data Master');
            }

            $arr_denumerator_iadp = array();
            $arr_numerator_iadp = array();
    
            foreach($month as $m) {
                $denumerator_iadp = 0;
                $numerator_iadp = 0;
                $pasien_ppi = PasienPpiDetail::whereYear('tgl_sensus', $request->tahun_filter)
                                            ->whereMonth('tgl_sensus', $m)
                                            ->get();
                foreach($pasien_ppi as $ppi) {
                    foreach($ppi->alat_digunakan_id as $data) {
                        if($data == $alat_iadp->id) {
                            $denumerator_iadp = $denumerator_iadp + 1;
                        }
                    }
                }
    
                foreach($pasien_ppi as $ppi) {
                    foreach($ppi->jenis_infeksi_rs as $data) {
                        if(array_key_exists("iadp", $data)) {
                            if($data["iadp"] != null) {
                                $numerator_iadp = $numerator_iadp + 1;
                            }
                        }
                    }
                }
                $mon = date("F", mktime(0, 0, 0, $m, 1));
                $arr_denumerator_iadp[$mon] = $denumerator_iadp;
                $arr_numerator_iadp[$mon] = $numerator_iadp;
            }
            
            $data = array();
            foreach($arr_denumerator_iadp as $key => $value) {
                $data["bulan"] = $key;
                $data["numerator"] = $arr_numerator_iadp[$key];
                $data["denumerator"] = $value;
                $data["capaian"] = $this->_iadp($arr_numerator_iadp[$key], $arr_denumerator_iadp[$key]);
                $data["target"] = null;
                $data["rata_rata"] = 2.32;
                $data["analisis"] = null;
                $data["tindak_lanjut"] = null;
                $data["tahun"] = $request->tahun_filter;
                $data["jenis_infeksi"] = $request->infeksi_rs;
                
                Export::updateOrCreate(
                    [
                        'tahun' => $request->tahun_filter,
                        'jenis_infeksi' => $request->infeksi_rs,
                        'bulan' => $key
                    ],$data
                );
            }

            return Excel::download(new IadpExport($request->tahun_filter), 'iadp.xlsx');
        }

        if($request->infeksi_rs == 'pleb') {
            $alat_pleb = $this->_alat('IV Line');
            if($alat_pleb == 'error') {
                return back()->with('error', 'Pastikan penamaan alat untuk PLEB adalah "IV Line" di Data Master');
            }

            $arr_denumerator_pleb = array();
            $arr_numerator_pleb = array();
    
            foreach($month as $m) {
                $denumerator_pleb = 0;
                $numerator_pleb = 0;
                $pasien_ppi = PasienPpiDetail::whereYear('tgl_sensus', $request->tahun_filter)
                                            ->whereMonth('tgl_sensus', $m)
                                            ->get();
                foreach($pasien_ppi as $ppi) {
                    foreach($ppi->alat_digunakan_id as $data) {
                        if($data == $alat_pleb->id) {
                            $denumerator_pleb = $denumerator_pleb + 1;
                        }
                    }
                }
    
                foreach($pasien_ppi as $ppi) {
                    foreach($ppi->jenis_infeksi_rs as $data) {
                        if(array_key_exists("pleb", $data)) {
                            if($data["pleb"] != null) {
                                $numerator_pleb = $numerator_pleb + 1;
                            }
                        }
                    }
                }
                $mon = date("F", mktime(0, 0, 0, $m, 1));
                $arr_denumerator_pleb[$mon] = $denumerator_pleb;
                $arr_numerator_pleb[$mon] = $numerator_pleb;
            }
            
            $data = array();
            foreach($arr_denumerator_pleb as $key => $value) {
                $data["bulan"] = $key;
                $data["numerator"] = $arr_numerator_pleb[$key];
                $data["denumerator"] = $value;
                $data["capaian"] = $this->_pleb($arr_numerator_pleb[$key], $arr_denumerator_pleb[$key]);
                $data["target"] = 1;
                $data["rata_rata"] = 2.32;
                $data["analisis"] = null;
                $data["tindak_lanjut"] = null;
                $data["tahun"] = $request->tahun_filter;
                $data["jenis_infeksi"] = $request->infeksi_rs;
                
                Export::updateOrCreate(
                    [
                        'tahun' => $request->tahun_filter,
                        'jenis_infeksi' => $request->infeksi_rs,
                        'bulan' => $key
                    ],$data
                );
            }

            return Excel::download(new PlebExport($request->tahun_filter), 'pleb.xlsx');
        }

        if($request->infeksi_rs == 'ido') {
            if($request->tindakan_operasi_id == "pilih") {
                return back()->with('error', 'Tindakan Operasi belum dipilih');
            }

            $arr_denumerator_ido = array();
            $arr_numerator_ido = array();
    
            foreach($month as $m) {
                $denumerator_ido = 0;
                $numerator_ido = 0;
                $pasien_ppi = PasienPpiDetail::whereYear('tgl_sensus', $request->tahun_filter)
                                            ->whereMonth('tgl_sensus', $m)
                                            ->get();

                $denumerator_ido = PasienPpiDetail::whereYear('tgl_sensus', $request->tahun_filter)
                                            ->where('tindakan_operasi_id', $request->tindakan_operasi_id)
                                            ->whereMonth('tgl_sensus', $m)
                                            ->count();
    
                foreach($pasien_ppi as $ppi) {
                    foreach($ppi->jenis_infeksi_rs as $data) {
                        if(array_key_exists("ido", $data)) {
                            if($data["ido"] != null) {
                                $numerator_ido = $numerator_ido + 1;
                            }
                        }
                    }
                }
                $mon = date("F", mktime(0, 0, 0, $m, 1));
                $arr_denumerator_ido[$mon] = $denumerator_ido;
                $arr_numerator_ido[$mon] = $numerator_ido;
            }
            
            $data = array();
            foreach($arr_denumerator_ido as $key => $value) {
                $data["bulan"] = $key;
                $data["numerator"] = $arr_numerator_ido[$key];
                $data["denumerator"] = $value;
                $data["capaian"] = $this->_ido($arr_numerator_ido[$key], $arr_denumerator_ido[$key]);
                $data["target"] = 2;
                $data["rata_rata"] = 2.32;
                $data["analisis"] = null;
                $data["tindak_lanjut"] = null;
                $data["tahun"] = $request->tahun_filter;
                $data["jenis_infeksi"] = $request->infeksi_rs;
                
                Export::updateOrCreate(
                    [
                        'tahun' => $request->tahun_filter,
                        'jenis_infeksi' => $request->infeksi_rs,
                        'bulan' => $key
                    ],$data
                );
            }

            return Excel::download(new IdoExport($request->tahun_filter), 'ido.xlsx');
        }
        // $denumerator_hap = $infeksi_rs['hap'];
        // if($denumerator_hap == 'error') {
        //     return back()->with('error', 'Pastikan penamaan alat untuk HAP adalah "ETT Ventilator" di Data Master');
        // }
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

    public function _hap($n, $d) {
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

    public function _isk($n, $d) {
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

    public function _iadp($n, $d) {
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

    public function _pleb($n, $d) {
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

    public function _ido($n, $d) {
        try {            
            $capaian = $n / $d * 100;
            return $capaian;
        } catch (Exception $e) {
            $err = $e->getMessage();
            if($err == "Division by zero") {
                return 0;
            }
            // return $e->getMessage();
        }
    }

    public function total_alat_digunakan($tgl_masuk = null, $tgl_keluar = null)
    {
        $model = AlatDigunakan::all();

        $arr_data = array();

        $nama_alat = array();
        foreach($model as $item) {
            array_push($nama_alat, $item->nama_alat_digunakan);
        }

        foreach($model as $data) 
        {
            if($tgl_masuk == null) {

                if($tgl_keluar == null) {
                    $ppi_detail = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                                            ->get();

                    $ppi_detail_distinct = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                                            ->groupBy('no_rm')
                                            ->get();

                } else {
                    $ppi_detail = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                                        ->where('tgl_keluar', $tgl_keluar)
                                        ->get();

                    $ppi_detail_distinct = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                                        ->where('tgl_keluar', $tgl_keluar)
                                        ->groupBy('no_rm')
                                        ->get();
                    // $pasien_ppi = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                    //                         ->where('tgl_keluar', $tgl_keluar)
                    //                         ->where('alat_digunakan_id', $data->id)
                    //                         ->count();

                    // $jumlah_pasien = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                    //                     ->where('tgl_keluar', $tgl_keluar)
                    //                     ->where('alat_digunakan_id', $data->id)
                    //                     ->distinct('no_rm')
                    //                     ->count();
                // End
                }
            } else {
                if($tgl_keluar == null) {
                    $ppi_detail = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                                        ->where('tgl_masuk', $tgl_masuk)
                                        ->get();

                    $ppi_detail_distinct = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                                        ->where('tgl_masuk', $tgl_masuk)
                                        ->groupBy('no_rm')
                                        ->get();
                    // $pasien_ppi = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                    //                         ->where('tgl_masuk', $tgl_masuk)
                    //                         ->where('alat_digunakan_id', $data->id)
                    //                         ->count();

                    // $jumlah_pasien = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                    //                     ->where('tgl_masuk', $tgl_masuk)
                    //                     ->where('alat_digunakan_id', $data->id)
                    //                     ->distinct('no_rm')
                    //                     ->count();
                // End
                } else {
                    $ppi_detail = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                                        ->where('tgl_masuk', $tgl_masuk)
                                        ->where('tgl_keluar', $tgl_keluar)
                                        ->get();

                    $ppi_detail_distinct = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                                        ->where('tgl_masuk', $tgl_masuk)
                                        ->where('tgl_keluar', $tgl_keluar)
                                        ->groupBy('no_rm')
                                        ->get();
                    // $pasien_ppi = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                    //                         ->where('tgl_masuk', $tgl_masuk)
                    //                         ->where('tgl_keluar', $tgl_keluar)
                    //                         ->where('alat_digunakan_id', $data->id)
                    //                         ->count();

                    // $jumlah_pasien = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                    //                     ->where('tgl_masuk', $tgl_masuk)
                    //                     ->where('tgl_keluar', $tgl_keluar)
                    //                     ->where('alat_digunakan_id', $data->id)
                    //                     ->distinct('no_rm')
                    //                     ->count();
                // End
                }
            }
            // $arr_data[$data->nama_alat_digunakan] = [
            //     'jumlah_pasien' => $jumlah_pasien,
            //     'jumlah_hari' => $pasien_ppi
            // ];

            $nama_alat[$data->nama_alat_digunakan] = 0;
            foreach($ppi_detail as $detail) {
                foreach(json_decode($detail->alat_digunakan_id) as $alat) {
                    if($alat == $data->id) {
                        $nama_alat[$data->nama_alat_digunakan] = $nama_alat[$data->nama_alat_digunakan] + 1;
                        $jumlah_hari = $nama_alat[$data->nama_alat_digunakan];
                    }
                }
            }
            $nama_alat[$data->nama_alat_digunakan] = 0;
            foreach($ppi_detail_distinct as $detail) {
                foreach(json_decode($detail->alat_digunakan_id) as $alat) {
                    if($alat == $data->id) {
                        $nama_alat[$data->nama_alat_digunakan] = $nama_alat[$data->nama_alat_digunakan] + 1;
                        $jumlah_pasien = $nama_alat[$data->nama_alat_digunakan];
                    }
                }
            }

            $arr_data[$data->nama_alat_digunakan] = [
                'jumlah_pasien' => isset($jumlah_pasien) ? $jumlah_pasien: 0,
                'jumlah_hari' => isset($jumlah_hari) ? $jumlah_hari: 0
            ];
        }
        // dd($arr_data);

        return $arr_data;
    }

    public function total_kegiatan_sensus($tgl_masuk = null, $tgl_keluar = null)
    {
        $model = KegiatanSensus::all();

        $arr_data = array();
        foreach($model as $data) 
        {
            if($tgl_masuk == null) {
                if($tgl_keluar == null) {
                    $pasien_ppi = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                                            ->where('kegiatan_sensus_id', $data->id)
                                            ->count();
                } else {
                    $pasien_ppi = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                                            ->where('tgl_keluar', $tgl_keluar)
                                            ->where('kegiatan_sensus_id', $data->id)
                                            ->count();
                }
            } else {
                if($tgl_keluar == null) {
                    $pasien_ppi = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                                            ->where('tgl_masuk', $tgl_masuk)
                                            ->where('kegiatan_sensus_id', $data->id)
                                            ->count();
                } else {
                    $pasien_ppi = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                                            ->where('tgl_masuk', $tgl_masuk)
                                            ->where('tgl_keluar', $tgl_keluar)
                                            ->where('kegiatan_sensus_id', $data->id)
                                            ->count();
                }
            }
            $arr_data[$data->nama_kegiatan_sensus] = $pasien_ppi;
        }

        return $arr_data;
    }

    public function total_jenis_operasi($tgl_masuk = null, $tgl_keluar = null)
    {
        $model = JenisOperasi::all();

        $arr_data = array();
        foreach($model as $data) 
        {
            if($tgl_masuk == null) {
                if($tgl_keluar == null) {
                    $pasien_ppi = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                                            ->where('jenis_operasi_id', $data->id)
                                            ->count();
                } else {
                    $pasien_ppi = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                                            ->where('tgl_keluar', $tgl_keluar)
                                            ->where('jenis_operasi_id', $data->id)
                                            ->count();
                }
            } else {
                if($tgl_keluar == null) {
                    $pasien_ppi = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                                            ->where('tgl_masuk', $tgl_masuk)
                                            ->where('jenis_operasi_id', $data->id)
                                            ->count();
                } else {
                    $pasien_ppi = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                                            ->where('tgl_masuk', $tgl_masuk)
                                            ->where('tgl_keluar', $tgl_keluar)
                                            ->where('jenis_operasi_id', $data->id)
                                            ->count();
                }
            }
            $arr_data[$data->nama_jenis_operasi] = $pasien_ppi;
        }

        return $arr_data;
    }

    public function total_lama_operasi($tgl_masuk = null, $tgl_keluar = null)
    {
        $model = LamaOperasi::all();

        $arr_data = array();
        foreach($model as $data) 
        {
            if($tgl_masuk == null) {
                if($tgl_keluar == null) {
                    $pasien_ppi = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                                            ->where('lama_operasi_id', $data->id)
                                            ->count();
                } else {
                    $pasien_ppi = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                                            ->where('tgl_keluar', $tgl_keluar)
                                            ->where('lama_operasi_id', $data->id)
                                            ->count();
                }
            } else {
                if($tgl_keluar == null) {
                    $pasien_ppi = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                                            ->where('tgl_masuk', $tgl_masuk)
                                            ->where('lama_operasi_id', $data->id)
                                            ->count();
                } else {
                    $pasien_ppi = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                                            ->where('tgl_masuk', $tgl_masuk)
                                            ->where('tgl_keluar', $tgl_keluar)
                                            ->where('lama_operasi_id', $data->id)
                                            ->count();
                }
            }
            $arr_data[$data->nama_lama_operasi] = $pasien_ppi;
        }

        return $arr_data;
    }

    public function total_asa_score($tgl_masuk = null, $tgl_keluar = null)
    {
        $model = AsaScore::all();

        $arr_data = array();
        foreach($model as $data) 
        {
            if($tgl_masuk == null) {
                if($tgl_keluar == null) {
                    $pasien_ppi = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                                            ->where('asa_score_id', $data->id)
                                            ->count();
                } else {
                    $pasien_ppi = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                                            ->where('tgl_keluar', $tgl_keluar)
                                            ->where('asa_score_id', $data->id)
                                            ->count();
                }
            } else {
                if($tgl_keluar == null) {
                    $pasien_ppi = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                                            ->where('tgl_masuk', $tgl_masuk)
                                            ->where('asa_score_id', $data->id)
                                            ->count();
                } else {
                    $pasien_ppi = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                                            ->where('tgl_masuk', $tgl_masuk)
                                            ->where('tgl_keluar', $tgl_keluar)
                                            ->where('asa_score_id', $data->id)
                                            ->count();
                }
            }
            $arr_data[$data->nama_asa_score] = $pasien_ppi;
        }

        return $arr_data;
    }

    public function total_risk_score($tgl_masuk = null, $tgl_keluar = null)
    {
        $model = RiskScore::all();

        $arr_data = array();
        foreach($model as $data) 
        {
            if($tgl_masuk == null) {
                if($tgl_keluar == null) {
                    $pasien_ppi = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                                            ->where('risk_score_id', $data->id)
                                            ->count();
                } else {
                    $pasien_ppi = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                                            ->where('tgl_keluar', $tgl_keluar)
                                            ->where('risk_score_id', $data->id)
                                            ->count();
                }
            } else {
                if($tgl_keluar == null) {
                    $pasien_ppi = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                                            ->where('tgl_masuk', $tgl_masuk)
                                            ->where('risk_score_id', $data->id)
                                            ->count();
                } else {
                    $pasien_ppi = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                                            ->where('tgl_masuk', $tgl_masuk)
                                            ->where('tgl_keluar', $tgl_keluar)
                                            ->where('risk_score_id', $data->id)
                                            ->count();
                }
            }
            $arr_data[$data->nama_risk_score] = $pasien_ppi;
        }

        return $arr_data;
    }

    public function total_jenis_infeksi_rs($tgl_masuk = null, $tgl_keluar = null)
    {
        $arr_data = array();
        $model = [
            'vap' => 0,
            'hap' => 0,
            'isk' => 0,
            'iadp' => 0,
            'ido' => 0,
            'pleb' => 0,
            'tirah_baring' => 0
        ];
        // dd($model);
        // foreach($model as $data) 
        // {
            if($tgl_masuk == null) {
                if($tgl_keluar == null) {
                    $pasien_ppi = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                                            ->get();
                } else {
                    $pasien_ppi = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                                            ->where('tgl_keluar', $tgl_keluar)
                                            ->get();
                }
            } else {
                if($tgl_keluar == null) {
                    $pasien_ppi = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                                            ->where('tgl_masuk', $tgl_masuk)
                                            ->get();
                } else {
                    $pasien_ppi = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                                            ->where('tgl_masuk', $tgl_masuk)
                                            ->where('tgl_keluar', $tgl_keluar)
                                            ->get();
                }
            }

            foreach($pasien_ppi as $ppi) {
                // dd(json_decode($ppi->jenis_infeksi_rs));
                foreach(json_decode($ppi->jenis_infeksi_rs) as $data) {
                    foreach($model as $iterasi => $value) {
                        if(isset($data->$iterasi)) {
                            if($data->$iterasi != null) {
                                $model[$iterasi] = $model[$iterasi] + 1;
                            }
                            $arr_data[$iterasi] = $model[$iterasi];
                        }
                    }
                }
            }
        // }
            // dd($arr_data);
        return $arr_data;
    }

    // Fungsi menghitung total pemakaian antibiotik berdasarkan kategori
    public function total_antibiotik($tgl_masuk = null, $tgl_keluar = null)
    {
        $arr_data = array();
        
        // $kategori_antibiotik = KategoriAntibiotik::all();

        $model = [
            'profilaksis' => 0,
            'terapi' => 0,
        ];

        // foreach($kategori_antibiotik as $item) 
        // {
        //     $model[$item->id] = 0;
        // }
        if($tgl_masuk == null) {
            if($tgl_keluar == null) {
                $pasien_ppi = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                                        ->get();
            } else {
                $pasien_ppi = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                                        ->where('tgl_keluar', $tgl_keluar)
                                        ->get();
            }
        } else {
            if($tgl_keluar == null) {
                $pasien_ppi = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                                        ->where('tgl_masuk', $tgl_masuk)
                                        ->get();
            } else {
                $pasien_ppi = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                                        ->where('tgl_masuk', $tgl_masuk)
                                        ->where('tgl_keluar', $tgl_keluar)
                                        ->get();
            }
        }

        foreach($pasien_ppi as $ppi) {
            if($ppi->antibiotik == null) {
                continue;
            } else {
                foreach(json_decode($ppi->antibiotik) as $data) {
                    foreach($data as $v) {
                        // dd($v);
                        $model[$v] = $model[$v] + 1;
                        $arr_data[$v] = $model[$v];
                    }
                }
            }
        }

        return $arr_data;
    }

    public function total_jenis_pendukung_kultur($tgl_masuk = null, $tgl_keluar = null) {

        $arr_data = array();
        $model = [
            'darah' => 0,
            'sputurn' => 0,
            'swab_luka' => 0,
            'urine' => 0,
        ];
        // dd($model);
        // foreach($model as $data) 
        // {
            if($tgl_masuk == null) {
                if($tgl_keluar == null) {
                    $pasien_ppi = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                                            ->get();
                } else {
                    $pasien_ppi = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                                            ->where('tgl_keluar', $tgl_keluar)
                                            ->get();
                }
            } else {
                if($tgl_keluar == null) {
                    $pasien_ppi = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                                            ->where('tgl_masuk', $tgl_masuk)
                                            ->get();
                } else {
                    $pasien_ppi = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                                            ->where('tgl_masuk', $tgl_masuk)
                                            ->where('tgl_keluar', $tgl_keluar)
                                            ->get();
                }
            }

            foreach($pasien_ppi as $ppi) {
                foreach(json_decode($ppi->jenis_kultur_pendukun_hais) as $data) {
                    foreach($model as $iterasi => $value) {
                        if(isset($data->$iterasi)) {
                            if($data->$iterasi != null) {
                                $model[$iterasi] = $model[$iterasi] + 1;
                            }
                            $arr_data[$iterasi] = $model[$iterasi];
                        }
                    }
                }
            }
        // }
            // dd($arr_data);
        return $arr_data;
    }

    public function rekap_denumerator(Request $request)
    {
        if($request->bulan_rekap == "pilih" || $request->tahun_rekap == null) {
            return back()->with('error-rekap', 'Tahun/Bulan belum dipilih!');
        }
        if($request->has('tombol-rekap')) {
            return Excel::download(new RekapExport($request->tahun_rekap, $request->bulan_rekap), 'rekap-alat.xlsx');
        }
        if($request->has('tombol-kultur')) {
            return Excel::download(new RekapPasienExport($request->tahun_rekap, $request->bulan_rekap), 'rekap-pasien.xlsx');
        }
    }
}
