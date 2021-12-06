<?php

namespace App\Http\Controllers;

use App\Models\Antibiotik;
use App\Models\AntibiotikTmp;
use App\Models\KategoriAntibiotik;
use Illuminate\Http\Request;

class AntibiotikController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()) {
            $data = Antibiotik::all();
            return datatables()
                ->of($data)
                ->addIndexColumn()
                ->editColumn('action', function($row) {
                    $button = '<div class="btn-group btn-group-sm" role="group">';
                    $button .= '<button href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-info btn-sm edit-post"><i class="fas fa-edit"></i></button>';
                    $button .= '<button type="button" name="delete" id="'.$row->id.'" class="delete btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>';
                    // $button .= '<button href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->id.'" data-originial-title="Upload" class="upload btn btn-sm btn-success shadow-sm upload-post" id="tombol-upload"><i class="fas fa-upload"></i></button>';
                    // $button .= '<a href="javascript:void(0)" data-target="#myModal" data-url="'.route('bus_detail.show', $row->id).'" data-toggle="modal" data-id="'.$row->id.'" data-original-title="View" class="view btn btn btn-warning btn-sm view-post"><i class="fas fa-eye"></i></a>';
                    $button .= '</div>';

                    return $button;
                })
                ->editColumn('kategori_antibiotik_id', function($row) {
                    return $row->kategori_antibiotik->nama_kategori_antibiotik;
                })
                ->editColumn('is_active', function($row) {
                    if($row->is_active == 1) {
                        return '<span class="badge badge-success">Active</span>';
                    } else {
                        return '<span class="badge badge-danger">Not Active</span>';
                    }
                })
                ->escapeColumns([])
                ->make(true);
        }
        $kategori_antibiotiks = KategoriAntibiotik::all();
        return view('antibiotik.admin.index', compact(
            'kategori_antibiotiks'
        ));
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
        if($request->has('is_active')) {
            $is_active = 1;
        } else {
            $is_active = 0;
        }
        
        $id = $request->id;

        $post = Antibiotik::updateOrCreate(['id' => $id],[
            'nama_antibiotik' => $request->nama_antibiotik,
            'jumlah' => $request->jumlah,
            'tanggal_awal' => $request->tanggal_awal,
            'tanggal_akhir' => $request->tanggal_akhir,
            'kategori_antibiotik_id' => $request->kategori_antibiotik_id,
            'is_active' => $is_active
        ]);

        return response()->json($post);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Antibiotik  $antibiotik
     * @return \Illuminate\Http\Response
     */
    public function show(Antibiotik $antibiotik)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Antibiotik  $antibiotik
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $where = array('id' => $id);
        $post  = Antibiotik::where($where)->first();

        return response()->json($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Antibiotik  $antibiotik
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Antibiotik $antibiotik)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Antibiotik  $antibiotik
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $where = array('id' => $id);
        $post = Antibiotik::where($where)->delete();
        return response()->json($post);
    }

    public function simpan_antibiotik_tmp(Request $request)
    {
        // dd($request->all());
        if($request->has('is_input_kategori')) {
            $no_rm = $request->no_rm_tmp;
            $antibiotik_tmp = AntibiotikTmp::where('no_rm', $no_rm)->get();
            $model = array();
            foreach($antibiotik_tmp as $item)
            {
                $keduanya = 'kategori_antibiotik_'.$no_rm.'_'.$item->antibiotik_id.'_keduanya';
                if($request->has($keduanya)) {
                    AntibiotikTmp::where('antibiotik_id', $item->antibiotik_id)
                                    ->where('no_rm', $no_rm)
                                    ->update([
                                        'kategori' => null,
                                        'keduanya' => 1,
                                    ]);
                } else {
                    $tes = 'kategori_antibiotik_'.$no_rm.'_'.$item->antibiotik_id;
                    if($request->has($tes)) {
                        AntibiotikTmp::where('antibiotik_id', $item->antibiotik_id)
                                    ->where('no_rm', $no_rm)
                                    ->update([
                                        'kategori' => $request->$tes,
                                        'keduanya' => 0,
                                    ]);
                    }
                }
            }
            $antibiotik_tmp = AntibiotikTmp::where('no_rm', $no_rm)->get();
            return response()->json($antibiotik_tmp);
        }

        $data = json_decode($request->data);

        $cek = AntibiotikTmp::where('no_rm', $request->no_rm)->first();

        if($cek == null) {
            // dd($request->all());
            foreach($data as $item)
            {
                $antibiotik = Antibiotik::find($item);
                AntibiotikTmp::updateOrCreate([
                    'antibiotik_id' => $antibiotik->id,
                    'no_rm' => $request->no_rm
                ],[
                    'no_rm' => $request->no_rm,
                    'nama_antibiotik' => $antibiotik->nama_antibiotik,
                    'antibiotik_id' => $antibiotik->id,
                    'kategori' => null,
                    'keduanya' => 0,
                ]);
            }
    
            $antibiotik_tmp = AntibiotikTmp::where('no_rm', $request->no_rm)->get();
            $table = view('pasien.admin.table', compact(
                'antibiotik_tmp'
            ))->render();
        } else {
            // dd($request->all());
            $a = AntibiotikTmp::where('no_rm', $request->no_rm)
                                ->whereNull('kategori')
                                ->first();
            
            if($a != null) {
                if($a->keduanya != null || $a->keduanya != 0) {
                // if($b != null) {
                    // AntibiotikTmp::where('no_rm', $request->no_rm)->delete();
                    foreach($data as $item)
                    {
                        $antibiotik = Antibiotik::find($item);

                        $b = AntibiotikTmp::where('no_rm', $request->no_rm)
                                    ->where('antibiotik_id',$item)
                                    ->first();

                        if($b != null) {
                            if($b->keduanya == 1) {   
                                // AntibiotikTmp::where('no_rm', $request->no_rm)->delete();                             
                                AntibiotikTmp::updateOrCreate([
                                    'antibiotik_id' => $antibiotik->id,
                                    'no_rm' => $request->no_rm
                                ],[
                                    'no_rm' => $request->no_rm,
                                    'nama_antibiotik' => $antibiotik->nama_antibiotik,
                                    'antibiotik_id' => $antibiotik->id,
                                    'kategori' => null,
                                    'keduanya' => 1,
                                ]);
                            }
                        } else {
                            AntibiotikTmp::updateOrCreate([
                                'antibiotik_id' => $antibiotik->id,
                                'no_rm' => $request->no_rm
                            ],[
                                'no_rm' => $request->no_rm,
                                'nama_antibiotik' => $antibiotik->nama_antibiotik,
                                'antibiotik_id' => $antibiotik->id,
                                'kategori' => null,
                                'keduanya' => 0,
                            ]);
                        }
                    }
                } else {
                    AntibiotikTmp::where('no_rm', $request->no_rm)->delete();
                    foreach($data as $item)
                    {
                        $antibiotik = Antibiotik::find($item);
                        AntibiotikTmp::updateOrCreate([
                            'antibiotik_id' => $antibiotik->id,
                            'no_rm' => $request->no_rm
                        ],[
                            'no_rm' => $request->no_rm,
                            'nama_antibiotik' => $antibiotik->nama_antibiotik,
                            'antibiotik_id' => $antibiotik->id,
                            'kategori' => null,
                            'keduanya' => 0,
                        ]);
                    }
                }
        
                $antibiotik_tmp = AntibiotikTmp::where('no_rm', $request->no_rm)->get();
                $table = view('pasien.admin.table', compact(
                    'antibiotik_tmp'
                ))->render();
            } else {
                // dd($data);
                // dd($request->all());
                $count = AntibiotikTmp::where('no_rm', $request->no_rm)
                                        ->whereNotNull('kategori')
                                        ->count();

                if($count > count($data)) {
                    foreach($data as $d) {
                        $an = AntibiotikTmp::where('no_rm', $request->no_rm)
                                            ->where('antibiotik_id', '!=',$d)
                                            ->delete();
                    }
                } else if($count < count($data)) {
                    foreach($data as $d) {
                        $periksa = AntibiotikTmp::where('antibiotik_id', $d)
                                        ->where('no_rm', $request->no_rm)
                                        ->first();

                        if($periksa == null) {
                            $antibiotik = Antibiotik::find($d);
                            $an = AntibiotikTmp::updateOrCreate([
                                'antibiotik_id' => $d,
                                'no_rm' => $request->no_rm
                            ],[
                                'no_rm' => $request->no_rm,
                                'nama_antibiotik' => $antibiotik->nama_antibiotik,
                                'antibiotik_id' => $antibiotik->id,
                                'kategori' => null,
                                'keduanya' => 0,
                            ]);
                        }
                    }
                }
    
                $antibiotik_tmp = AntibiotikTmp::where('no_rm', $request->no_rm)->get();
                $table = view('pasien.admin.table', compact(
                    'antibiotik_tmp'
                ))->render();
            }
        }

        return response()->json( array('success' => true, 'table'=>$table) );
    }
    
    public function reset_antibiotik_tmp($no_rm)
    {
        $delete = AntibiotikTmp::where('no_rm', $no_rm)->delete();
        return response()->json($delete);
    }
}
