<?php

namespace App\Http\Controllers;

use App\Models\AlatDigunakan;
use App\Models\AsaScore;
use App\Models\Diagnosa;
use App\Models\JenisOperasi;
use App\Models\KegiatanSensus;
use App\Models\LamaOperasi;
use App\Models\PasienPpi;
use App\Models\RiskScore;
use App\Models\Ruang;
use App\Models\TindakanOperasi;
use App\Models\Transmisi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PasienPpiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $ppi = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                            ->get();

        if($request->ajax()) {
            return datatables()
            ->of($ppi)
            ->addIndexColumn()
            ->editColumn('tgl_masuk_keluar', function($row) {
                return $row->tgl_masuk.' / '.$row->tgl_keluar;
            })
            ->editColumn('ruang_id', function($row) {
                $ruang = Ruang::find($row->ruang_id);
                return $ruang->nama_ruang;
            })
            ->editColumn('operasi', function($row) {
                if($row->is_operasi == 0) {
                    return '<span class="badge badge-info">Tidak Operasi</span>';
                } else {
                    $s = '<span class="badge badge-danger">Operasi</span>';
                    $s .= ' / ';
                    if($row->tgl_operasi != null) {
                        $s .= '<span class="badge badge-danger">'.Carbon::parse($row->tgl_operasi)->isoFormat('D MMMM Y').'</span>';
                    }
                    return $s;
                }
            })
            ->editColumn('oprs1', function($row) {
                if($row->is_operasi == 0) {
                    return '-';
                } else {
                    if($row->tindakan_operasi_id == null) {
                        $tindakan = null;
                    } else {
                        $tindakan = TindakanOperasi::find($row->tindakan_operasi_id)->nama_tindakan_operasi;
                    }
                    $jenis = JenisOperasi::find($row->jenis_operasi_id)->nama_jenis_operasi;
                    $lama = LamaOperasi::find($row->lama_operasi_id)->nama_lama_operasi;
                    return $tindakan.'/'.$jenis.'/'.$lama;
                }
            })
            ->editColumn('oprs2', function($row) {
                if($row->is_operasi == 0) {
                    return '-';
                } else {
                    $asa = AsaScore::find($row->asa_score_id)->nama_asa_score;
                    $risk = RiskScore::find($row->risk_score_id)->nama_risk_score;
                    return $asa.'/'.$risk;
                }
            })
            ->editColumn('tgl_kegiatan_sensus', function($row) {
                $kegiatan = KegiatanSensus::find($row->kegiatan_sensus_id)->nama_kegiatan_sensus;
                return Carbon::parse($row->tgl_sensus)->isoFormat('D MMMM Y').' / '.$kegiatan;
            })
            ->editColumn('diagnosa', function($row) {
                $diag = '';
                foreach(json_decode($row->diagnosa) as $d) {
                    $dd = Diagnosa::where('DiagnosisCode', $d)->first()->DiagnosisName;
                    $diag .= $dd.', ';
                }

                return $diag;
            })
            ->editColumn('alat', function($row) {
                $alat = '';
                foreach(json_decode($row->alat_digunakan_id) as $a) {
                    $aa = AlatDigunakan::find($a)->nama_alat_digunakan;
                    $alat .= $aa.', ';
                }

                return $alat;
            })
            ->editColumn('infeksi_rs', function($row) {
                $infeksi = '';
                $model = [
                    'vap',
                    'hap',
                    'isk',
                    'iadp',
                    'ido',
                    'pleb',
                    'tirah_baring'
                ];
                foreach(json_decode($row->jenis_infeksi_rs) as $val) {
                    foreach($model as $m) {
                        if(isset($val->$m)) {
                            if($val->$m != null) {
                                $infeksi .= str_replace('_',' ',$m).', ';
                            }
                        }
                    }
                }
                return $infeksi;
            })
            // ->editColumn('infeksi_lain', function($row) {
            //     $infeksi = '';
            //     $model = [
            //         'vap',
            //         'hap',
            //         'isk',
            //         'iadp',
            //         'ido',
            //         'pleb',
            //     ];
            //     if($row->infeksi_rs_lain != null) {
            //         foreach(json_decode($row->infeksi_rs_lain) as $val) {
            //             foreach($model as $m) {
            //                 if(isset($val->$m)) {
            //                     if($val->$m != null) {
            //                         $infeksi .= $m.', ';
            //                     }
            //                 }
            //             }
            //         }
            //         return $infeksi;
            //     } else {
            //         return '-';
            //     }
            // })
            ->editColumn('kultur', function($row) {
                $kultur = '';
                $model = [
                    'darah',
                    'sputurn',
                    'swab_luka',
                    'urine'
                ];
                foreach(json_decode($row->jenis_kultur_pendukun_hais) as $val) {
                    foreach($model as $m) {
                        if(isset($val->$m)) {
                            if($val->$m != null) {
                                $kultur .= str_replace('_',' ',$m).', ';
                            }
                        }
                    }
                }
                return $kultur;
            })
            ->editColumn('transmisi_id', function($row) {
                if($row->transmisi_id != null) {
                    $transmisi = Transmisi::find($row->transmisi_id)->nama_transmisi;
                    return $transmisi;
                } else {
                    return '-';
                }
            })
            ->escapeColumns([])
            ->make(true);
        }

        return view('ppi.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PasienPpi  $pasienPpi
     * @return \Illuminate\Http\Response
     */
    public function show(PasienPpi $pasienPpi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PasienPpi  $pasienPpi
     * @return \Illuminate\Http\Response
     */
    public function edit(PasienPpi $pasienPpi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PasienPpi  $pasienPpi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PasienPpi $pasienPpi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PasienPpi  $pasienPpi
     * @return \Illuminate\Http\Response
     */
    public function destroy(PasienPpi $pasienPpi)
    {
        //
    }
}
