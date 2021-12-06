<?php

namespace App\Http\Controllers;

use App\Models\AlatDigunakan;
use App\Models\Antibiotik;
use App\Models\AntibiotikTmp;
use App\Models\AsaScore;
use App\Models\InfeksiRsLain;
use App\Models\JenisInfeksiRs;
use App\Models\JenisKuman;
use App\Models\JenisOperasi;
use App\Models\KegiatanSensus;
use App\Models\LamaOperasi;
use App\Models\PasienFromOtherDb;
use App\Models\PasienPpi;
use App\Models\PasienPpiDetail;
use App\Models\Patient;
use App\Models\Registration;
use App\Models\RiskScore;
use App\Models\Ruang;
use App\Models\TindakanOperasi;
use App\Models\Transmisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PasienFromOtherDbController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
                    // $data = DB::connection('mysql2')
                    //     ->statement("SELECT * FROM registrationpatient LIMIT 10");
                    //     dd($data);
        // $data = Registration::select('patient.PatientName','registration.*')
        //                     ->leftjoin('patient', function($join) {
        //                         $join->on('patient.MedicalNo','=','registration.MedicalNo');
        //                     })
        //                     ->orderBy('RegistrationDateTime','asc')->limit(5);
        $data = Registration::with('patient')->select('registration.*');
        // dd($data);
        if($request->ajax()) {
            return datatables()
            ->of($data)
            ->addIndexColumn()
            ->editColumn('action', function($row) {
                $regNo = str_replace('/','-',$row->RegistrationNo);
                return '<a target="_blank" href="'.route('pasien.show',['pasien' => $regNo]).'" type="button" data-medrec="'.$row->MedicalNo.'" id="proses-pasien" class="proses-pasien btn btn-sm btn-success btn-sm mr-2">Proses Data PPI</a>';
            })
            ->editColumn('patient.PatientName', function($row) {
                if($row->patient == null) {
                    return '<span class="badge badge-danger">Not Found</span>';
                }
                return $row->patient->PatientName;
            })
            ->editColumn('patient.GCSex', function($row) {
                if($row->patient == null) {
                    return '<span class="badge badge-danger">Not Found</span>';
                }
                if($row->patient->GCSex == '0001^F') {
                    return 'P';
                } else {
                    return 'L';
                }
            })
            ->editColumn('patient.CityOfBirth', function($row) {
                if($row->patient == null) {
                    return '<span class="badge badge-danger">Not Found</span>';
                }
                return $row->patient->CityOfBirth;
            })
            ->editColumn('patient.DateOfBirth', function($row) {
                if($row->patient == null) {
                    return '<span class="badge badge-danger">Not Found</span>';
                }
                return $row->patient->DateOfBirth;
            })
            ->escapeColumns([])
            ->make(true);
        }
        
        return view('pasien.admin.index');
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
        // dd($request->all());
        if($request->no_rm == "-") {
            return response()->json([
                'success' => false,
                'message' => "Data Pasien Tidak bisa di Proses!"
            ], 200);
        }
        $data['diagnosa'] = array();
        $data['jenis_infeksi_rs'] = array();
        $data['jenis_kumen'] = $request->jenis_kumen;
        $data['jenis_kultur_pendukun_hais'] = array();
        $data['antibiotik'] = array();
        $data['infeksi_rs_lain'] = array();
        $data['alat_digunakan_id'] = array();

        if($request->hasfile('foto_hasil_rontgen')) {
            foreach($request->file('foto_hasil_rontgen') as $file) {
                $name = round(microtime(true)*1000).'-'.$file->getClientOriginalName();
                $file->move(public_path().'/uploads/', $name);  
                $imgData[] = $name; 
            }
            $data['foto_hasil_rontgen'] = json_encode($imgData);
        } else {
            $data['foto_hasil_rontgen'] = null;
        }

        if($request->ajax()) {
            $data_ppi = PasienPpi::updateOrCreate(
                [
                    'tgl_masuk' => $request->tgl_masuk,
                    'no_rm' => $request->no_rm,
                    'no_registration' => $request->no_registration
                ],
                [
                    'tgl_masuk' => $request->tgl_masuk,
                    'no_rm' => $request->no_rm,
                    'no_registration' => $request->no_registration,
                    'tgl_keluar' => $request->tgl_keluar == null || $request->tgl_keluar == '____-__-__' ? null: $request->tgl_keluar
                ]
            );
            $data['pasien_ppi_id'] = $data_ppi->id;
            if($request->has('ruang_id')) {
                $data['ruang_id'] = $request->ruang_id;
            } else {
                $data['ruang_id'] = null;
            }

            if($request->has('diagnosa')) {
                foreach($request->diagnosa as $diagnosa) {
                    array_push($data['diagnosa'], $diagnosa);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Diagnosa tidak boleh kosong!'
                ], 200);
            }

            if($request->has('is_operasi')) {
                if($request->is_operasi == 'ya') {
                    $data['is_operasi'] = 1;
                    $data['jenis_operasi_id'] = $request->jenis_operasi_id;
                    $data['lama_operasi_id'] = $request->lama_operasi_id;
                    $data['asa_score_id'] = $request->asa_score_id;
                    $data['risk_score_id'] = $request->risk_score_id;
                    $data['tindakan_operasi_id'] = $request->tindakan_operasi_id;
                }
            } else {
                $data['is_operasi'] = 0;
                $data['jenis_operasi_id'] = null;
                $data['lama_operasi_id'] = null;
                $data['asa_score_id'] = null;
                $data['risk_score_id'] = null;
                $data['tindakan_operasi_id'] = null;
            }

            $data['tgl_sensus'] = $request->tgl_sensus == null || $request->tgl_sensus == '____-__-__ __:__:__' ? null: $request->tgl_sensus;

            if($data['tgl_sensus'] == null)  {
                return response()->json([
                    'success' => false,
                    'message' => 'Tanggal sensus tidak boleh kosong!'
                ], 200);
            }

            if($request->has('alat_digunakan_id')) {
                // $data['alat_digunakan_id'] = $request->alat_digunakan_id;
                foreach($request->alat_digunakan_id as $item) {
                    array_push($data['alat_digunakan_id'], $item);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Alat yang digunakan tidak boleh kosong!'
                ], 200);
                // $data['alat_digunakan_id'] = null;
            }

            if($request->has('kegiatan_sensus_id')) {
                $data['kegiatan_sensus_id'] = $request->kegiatan_sensus_id;
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Kegiatan sensus tidak boleh kosong!'
                ], 200);
                // $data['kegiatan_sensus_id'] = null;
            }

            $data['hasil_rontgen'] = $request->hasil_rontgen;
            $data['tgl_rontgen'] = $request->tgl_rontgen == null || $request->tgl_rontgen == '____-__-__' ? null: $request->tgl_rontgen;

            if($request->has('jenis_infeksi_rs_vap')) {
                if($request->jenis_infeksi_rs_vap != null && $request->jenis_infeksi_rs_vap != '____-__-__') {

                    array_push($data['jenis_infeksi_rs'], ['vap' => $request->jenis_infeksi_rs_vap]);
                } else {
                    array_push($data['jenis_infeksi_rs'], ['vap' => null]);
                }
            }

            if($request->has('jenis_infeksi_rs_hap')) {
                if($request->jenis_infeksi_rs_hap != null && $request->jenis_infeksi_rs_hap != '____-__-__') {
                    array_push($data['jenis_infeksi_rs'], ['hap' => $request->jenis_infeksi_rs_hap]);
                } else {
                    array_push($data['jenis_infeksi_rs'], ['hap' => null]);
                }
            }

            if($request->has('jenis_infeksi_rs_isk')) {
                if($request->jenis_infeksi_rs_isk != null && $request->jenis_infeksi_rs_isk != '____-__-__') {
                    array_push($data['jenis_infeksi_rs'], ['isk' => $request->jenis_infeksi_rs_isk]);
                } else {
                    array_push($data['jenis_infeksi_rs'], ['isk' => null]);
                }
            }

            if($request->has('jenis_infeksi_rs_iadp')) {
                if($request->jenis_infeksi_rs_iadp != null && $request->jenis_infeksi_rs_iadp != '____-__-__') {
                    array_push($data['jenis_infeksi_rs'], ['iadp' => $request->jenis_infeksi_rs_iadp]);
                } else {
                    array_push($data['jenis_infeksi_rs'], ['iadp' => null]);
                }
            }

            if($request->has('jenis_infeksi_rs_ido')) {
                if($request->jenis_infeksi_rs_ido != null && $request->jenis_infeksi_rs_ido != '____-__-__') {
                    array_push($data['jenis_infeksi_rs'], ['ido' => $request->jenis_infeksi_rs_ido]);
                } else {
                    array_push($data['jenis_infeksi_rs'], ['ido' => null]);
                }
            }

            if($request->has('jenis_infeksi_rs_pleb')) {
                if($request->jenis_infeksi_rs_pleb != null && $request->jenis_infeksi_rs_pleb != '____-__-__') {
                    array_push($data['jenis_infeksi_rs'], ['pleb' => $request->jenis_infeksi_rs_pleb]);
                } else {
                    array_push($data['jenis_infeksi_rs'], ['pleb' => null]);
                }
            }

            if($request->has('jenis_infeksi_rs_tirah_baring')) {
                if($request->jenis_infeksi_rs_tirah_baring != null && $request->jenis_infeksi_rs_tirah_baring != '____-__-__') {
                    array_push($data['jenis_infeksi_rs'], ['tirah_baring' => $request->jenis_infeksi_rs_tirah_baring]);
                } else {
                    array_push($data['jenis_infeksi_rs'], ['tirah_baring' => null]);
                }
            }

            // if($request->has('jenis_kumen')) {
            //     foreach($request->jenis_kumen as $jenis_kumen) {
            //         array_push($data['jenis_kumen'], $jenis_kumen);
            //     }
            // } else {
            //     $data['jenis_kumen'] = null;
            // }

            $data['tgl_infeksi_kuman'] = $request->tgl_infeksi_kuman == null || $request->tgl_infeksi_kuman == '____-__-__' ? null: $request->tgl_infeksi_kuman;

            if($request->has('darah')) {
                array_push($data['jenis_kultur_pendukun_hais'], ['darah' => $request->darah]);
            } else {
                array_push($data['jenis_kultur_pendukun_hais'], ['darah' => null]);
            }

            if($request->has('sputurn')) {
                array_push($data['jenis_kultur_pendukun_hais'], ['sputurn' => $request->sputurn]);
            } else {
                array_push($data['jenis_kultur_pendukun_hais'], ['sputurn' => null]);
            }
            if($request->has('swab_luka')) {
                array_push($data['jenis_kultur_pendukun_hais'], ['swab_luka' => $request->swab_luka]);
            } else {
                array_push($data['jenis_kultur_pendukun_hais'], ['swab_luka' => null]);
            }

            if($request->has('urine')) {
                array_push($data['jenis_kultur_pendukun_hais'], ['urine' => $request->urine]);
            } else {
                array_push($data['jenis_kultur_pendukun_hais'], ['urine' => null]);
            }

            if($request->has('antibiotik')) {
                $count = AntibiotikTmp::where('no_rm', $request->no_rm)->count();

                if($count != count($request->antibiotik)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Antibiotik yang anda simpan dan pilih tidak sama!'
                    ], 200);
                } else {
                    foreach($request->antibiotik as $antibiotik) {
                        $antibiotik_tmp = AntibiotikTmp::where('no_rm', $request->no_rm)
                                                    ->where('antibiotik_id', $antibiotik)
                                                    ->first();
                        
                        if($antibiotik_tmp->kategori != null) {
                            array_push($data['antibiotik'], [$antibiotik => $antibiotik_tmp->kategori]);
                        }

                        if($antibiotik_tmp->keduanya == 1 && $antibiotik_tmp->kategori == null) {
                            array_push($data['antibiotik'], [$antibiotik => 'profilaksis']);
                            array_push($data['antibiotik'], [$antibiotik => 'terapi']);
                        }
                    }
                }
            } else {
                $antibiotik_tmp = AntibiotikTmp::where('no_rm', $request->no_rm)
                                                ->whereNotNull('kategori')
                                                ->get();
                if(count($antibiotik_tmp) > 0) {
                    foreach($antibiotik_tmp as $item) {
                        array_push($data['antibiotik'], [$item->antibiotik_id => $item->kategori]);
                    }
                } else {
                    $antibiotik_tmp = AntibiotikTmp::where('no_rm', $request->no_rm)
                                                ->where('keduanya', 1)
                                                ->get();
                    
                    if(count($antibiotik_tmp) > 0) {
                        foreach($antibiotik_tmp as $item) {
                            array_push($data['antibiotik'], [$item->antibiotik_id => 'profilaksis']);
                            array_push($data['antibiotik'], [$item->antibiotik_id => 'terapi']);
                        }
                    } else {
                        $data['antibiotik'] = null;
                    }
                }
            }

            if($request->has('transmisi_id')) {
                $data['transmisi_id'] = $request->transmisi_id;
            } else {
                $data['transmisi_id'] = null;
            }

            // if($request->has('infeksi_rs_lain')) {
            //     foreach($request->infeksi_rs_lain as $infeksi_rs_lain) {
            //         array_push($data['infeksi_rs_lain'], $infeksi_rs_lain);
            //     }
            // } else {
            //     $data['infeksi_rs_lain'] = null;
            // }

            if($request->has('jenis_infeksi_rs_vap_lain')) {
                if($request->jenis_infeksi_rs_vap_lain != null && $request->jenis_infeksi_rs_vap_lain != '____-__-__') {

                    array_push($data['infeksi_rs_lain'], ['vap' => $request->jenis_infeksi_rs_vap_lain]);
                } else {
                    array_push($data['infeksi_rs_lain'], ['vap' => null]);
                }
            }

            if($request->has('jenis_infeksi_rs_hap_lain')) {
                if($request->jenis_infeksi_rs_hap_lain != null && $request->jenis_infeksi_rs_hap_lain != '____-__-__') {
                    array_push($data['infeksi_rs_lain'], ['hap' => $request->jenis_infeksi_rs_hap_lain]);
                } else {
                    array_push($data['infeksi_rs_lain'], ['hap' => null]);
                }
            }

            if($request->has('jenis_infeksi_rs_isk_lain')) {
                if($request->jenis_infeksi_rs_isk_lain != null && $request->jenis_infeksi_rs_isk_lain != '____-__-__') {
                    array_push($data['infeksi_rs_lain'], ['isk' => $request->jenis_infeksi_rs_isk_lain]);
                } else {
                    array_push($data['infeksi_rs_lain'], ['isk' => null]);
                }
            }

            if($request->has('jenis_infeksi_rs_iadp_lain')) {
                if($request->jenis_infeksi_rs_iadp_lain != null && $request->jenis_infeksi_rs_iadp_lain != '____-__-__') {
                    array_push($data['infeksi_rs_lain'], ['iadp' => $request->jenis_infeksi_rs_iadp_lain]);
                } else {
                    array_push($data['infeksi_rs_lain'], ['iadp' => null]);
                }
            }

            if($request->has('jenis_infeksi_rs_ido_lain')) {
                if($request->jenis_infeksi_rs_ido_lain != null && $request->jenis_infeksi_rs_ido_lain != '____-__-__') {
                    array_push($data['infeksi_rs_lain'], ['ido' => $request->jenis_infeksi_rs_ido_lain]);
                } else {
                    array_push($data['infeksi_rs_lain'], ['ido' => null]);
                }
            }

            if($request->has('jenis_infeksi_rs_pleb_lain')) {
                if($request->jenis_infeksi_rs_pleb_lain != null && $request->jenis_infeksi_rs_pleb_lain != '____-__-__') {
                    array_push($data['infeksi_rs_lain'], ['pleb' => $request->jenis_infeksi_rs_pleb_lain]);
                } else {
                    array_push($data['infeksi_rs_lain'], ['pleb' => null]);
                }
            }

            // if($request->has('jenis_infeksi_rs_tirah_baring_lain')) {
            //     if($request->jenis_infeksi_rs_tirah_baring_lain != null && $request->jenis_infeksi_rs_tirah_baring_lain != '____-__-__') {
            //         array_push($data['infeksi_rs_lain'], ['tirah_baring' => $request->jenis_infeksi_rs_tirah_baring_lain]);
            //     } else {
            //         array_push($data['infeksi_rs_lain'], ['tirah_baring' => null]);
            //     }
            // }

            // dd($data);
            $ppi_detail = PasienPpiDetail::create($data);
            if($ppi_detail) {
                AntibiotikTmp::where('no_rm', $request->no_rm)->delete();
                return response()->json($ppi_detail);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PasienFromOtherDb  $pasienFromOtherDb
     * @return \Illuminate\Http\Response
     */
    public function show($registrationno)
    {
        $regNo = str_replace('-','/',$registrationno);
        $registration = Registration::where('RegistrationNo', $regNo)->first();

        $patient = Patient::where('MedicalNo', $registration->MedicalNo)->first();

        if($patient == null) {
            $data_ppi = null;
        } else {
            $data_ppi = PasienPpi::where('no_registration', $regNo)
                                ->where('no_rm', $patient->MedicalNo)
                                ->first();
        }

        if($data_ppi == null) {
            $data_ppi_detail = null;
            $count_data_ppi_detail = 0;
        } else {
            $data_ppi_detail = PasienPpiDetail::where('pasien_ppi_id', $data_ppi->id)->get();
            $count_data_ppi_detail = PasienPpiDetail::where('pasien_ppi_id', $data_ppi->id)->count();
        }
        // dd($data_ppi);

        // dump($data_ppi_detail);

        // dd($patient);
        $jenis_infeksi_rs = JenisInfeksiRs::all();
        $alat_digunakan = AlatDigunakan::all();
        return view('pasien.admin.show', compact(
            'patient',
            'registration',
            'jenis_infeksi_rs',
            'data_ppi_detail',
            'count_data_ppi_detail'
        ));
    }

    public function data_alat_digunakan(Request $request) {
        $in_alat = array();
        if($request->search == "") {
            if($request->has('no_rm')) {
                $pasien_detail = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                                            ->where('no_rm', $request->no_rm)
                                            ->whereDate('pasien_ppi_details.created_at', Carbon::today()->toDateString())
                                            ->get();
                if(!$pasien_detail->isEmpty()) {
                    foreach($pasien_detail as $ppi) {
                        foreach(json_decode($ppi->alat_digunakan_id) as $alat) {
                            $in_alat[] = $alat;
                        }
                    }
                }
            }
            $model = AlatDigunakan::whereNotIn('id', $in_alat)->get();
        } else {
            if($request->has('no_rm')) {
                $pasien_detail = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                                            ->where('no_rm', $request->no_rm)
                                            ->whereDate('pasien_ppi_details.created_at', Carbon::today()->toDateString())
                                            ->get();
                if(!$pasien_detail->isEmpty()) {
                    foreach($pasien_detail as $ppi) {
                        foreach(json_decode($ppi->alat_digunakan_id) as $alat) {
                            $in_alat[] = $alat;
                        }
                    }
                }
            }
            $model = AlatDigunakan::where('nama_alat_digunakan', 'like', '%'.$request->search.'%')
                                    ->whereNotIn('id', $in_alat)
                                    ->get();
        }

        $response = array();
        foreach($model as $data){
            $response[] = array(
                "id"=>$data->id,
                "text"=>$data->nama_alat_digunakan,
            );
        }

        return response()->json($response);
    }

    public function data_transmisi(Request $request) {
        if($request->search == "") {
            $model = Transmisi::all();
        } else {
            $model = Transmisi::where('nama_transmisi', 'like', '%'.$request->search.'%')->get();
        }

        $response = array();
        foreach($model as $data){
            $response[] = array(
                "id"=>$data->id,
                "text"=>$data->nama_transmisi,
            );
        }

        return response()->json($response);
    }

    public function data_antibiotik(Request $request) {
        if($request->search == "") {
            $model = Antibiotik::where('is_active', 1)->get();
        } else {
            $model = Antibiotik::where('nama_antibiotik', 'like', '%'.$request->search.'%')
                                ->where('is_active', 1)
                                ->get();
        }

        $response = array();
        foreach($model as $data){
            $response[] = array(
                "id"=>$data->id,
                "text"=>$data->nama_antibiotik,
            );
        }

        return response()->json($response);
    }

    public function data_infeksi_rs_lain(Request $request) {
        if($request->search == "") {
            $model = InfeksiRsLain::all();
        } else {
            $model = InfeksiRsLain::where('nama_infeksi_rs_lain', 'like', '%'.$request->search.'%')->get();
        }

        $response = array();
        foreach($model as $data){
            $response[] = array(
                "id"=>$data->id,
                "text"=>$data->nama_infeksi_rs_lain,
            );
        }

        return response()->json($response);
    }

    public function data_jenis_kuman(Request $request) {
        if($request->search == "") {
            $model = JenisKuman::all();
        } else {
            $model = JenisKuman::where('nama_jenis_kumen', 'like', '%'.$request->search.'%')->get();
        }

        $response = array();
        foreach($model as $data){
            $response[] = array(
                "id"=>$data->id,
                "text"=>$data->nama_jenis_kumen,
            );
        }

        return response()->json($response);
    }

    public function data_kegiatan_sensus(Request $request) {
        if($request->search == "") {
            $model = KegiatanSensus::all();
        } else {
            $model = KegiatanSensus::where('nama_kegiatan_sensus', 'like', '%'.$request->search.'%')->get();
        }

        $response = array();
        foreach($model as $data){
            $response[] = array(
                "id"=>$data->id,
                "text"=>$data->nama_kegiatan_sensus,
            );
        }

        return response()->json($response);
    }
    
    public function data_ruang(Request $request) {
        if($request->search == "") {
            $model = Ruang::all();
        } else {
            $model = Ruang::where('nama_ruang', 'like', '%'.$request->search.'%')->get();
        }

        $response = array();
        foreach($model as $data){
            $response[] = array(
                "id"=>$data->id,
                "text"=>$data->nama_ruang,
            );
        }

        return response()->json($response);
    }

    public function data_diagnosa(Request $request) {
        if($request->search == "") {
            $model = DB::connection('mysql2')->table('diagnosis')->limit(100)->get();
        } else {
            $model = DB::connection('mysql2')->table('diagnosis')->where('AlternateDiagnosisName', 'like', '%'.$request->search.'%')->get();
        }

        $response = array();
        foreach($model as $data){
            $response[] = array(
                "id"=>$data->DiagnosisCode,
                "text"=>$data->AlternateDiagnosisName,
            );
        }

        return response()->json($response);
    }

    public function data_jenis_operasi(Request $request) {
        if($request->search == "") {
            $model = JenisOperasi::all();
        } else {
            $model = JenisOperasi::where('nama_jenis_operasi', 'like', '%'.$request->search.'%')->get();
        }

        $response = array();
        foreach($model as $data){
            $response[] = array(
                "id"=>$data->id,
                "text"=>$data->nama_jenis_operasi,
            );
        }

        return response()->json($response);
    }

    public function data_tindakan_operasi(Request $request) {
        if($request->search == "") {
            $model = TindakanOperasi::all();
        } else {
            $model = TindakanOperasi::where('nama_tindakan_operasi', 'like', '%'.$request->search.'%')->get();
        }

        $response = array();
        foreach($model as $data){
            $response[] = array(
                "id"=>$data->id,
                "text"=>$data->nama_tindakan_operasi,
            );
        }

        return response()->json($response);
    }

    public function data_lama_operasi(Request $request) {
        if($request->search == "") {
            $model = LamaOperasi::all();
        } else {
            $model = LamaOperasi::where('nama_lama_operasi', 'like', '%'.$request->search.'%')->get();
        }

        $response = array();
        foreach($model as $data){
            $response[] = array(
                "id"=>$data->id,
                "text"=>$data->nama_lama_operasi,
            );
        }

        return response()->json($response);
    }

    public function data_asa_score(Request $request) {
        if($request->search == "") {
            $model = AsaScore::all();
        } else {
            $model = AsaScore::where('nama_asa_score', 'like', '%'.$request->search.'%')->get();
        }

        $response = array();
        foreach($model as $data){
            $response[] = array(
                "id"=>$data->id,
                "text"=>$data->nama_asa_score,
            );
        }

        return response()->json($response);
    }

    public function data_risk_score(Request $request) {
        if($request->search == "") {
            $model = RiskScore::all();
        } else {
            $model = RiskScore::where('nama_risk_score', 'like', '%'.$request->search.'%')->get();
        }

        $response = array();
        foreach($model as $data){
            $response[] = array(
                "id"=>$data->id,
                "text"=>$data->nama_risk_score,
            );
        }

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PasienFromOtherDb  $pasienFromOtherDb
     * @return \Illuminate\Http\Response
     */
    public function edit(PasienFromOtherDb $pasienFromOtherDb)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PasienFromOtherDb  $pasienFromOtherDb
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PasienFromOtherDb $pasienFromOtherDb)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PasienFromOtherDb  $pasienFromOtherDb
     * @return \Illuminate\Http\Response
     */
    public function destroy(PasienFromOtherDb $pasienFromOtherDb)
    {
        //
    }

    public function get_detail_with_id($id)
    {
        abort(400);
    }
}
