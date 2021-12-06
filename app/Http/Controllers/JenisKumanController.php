<?php

namespace App\Http\Controllers;

use App\Models\JenisKuman;
use Illuminate\Http\Request;

class JenisKumanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()) {
            $data = JenisKuman::all();
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
                ->escapeColumns([])
                ->make(true);
        }
        return view('jenis-kuman.admin.index');
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
        $id = $request->id;

        $post = JenisKuman::updateOrCreate(['id' => $id],[
            'nama_jenis_kumen' => $request->nama_jenis_kumen
        ]);

        return response()->json($post);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\JenisKuman  $jenisKuman
     * @return \Illuminate\Http\Response
     */
    public function show(JenisKuman $jenisKuman)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\JenisKuman  $jenisKuman
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $where = array('id' => $id);
        $post  = JenisKuman::where($where)->first();

        return response()->json($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\JenisKuman  $jenisKuman
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JenisKuman $jenisKuman)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\JenisKuman  $jenisKuman
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $where = array('id' => $id);
        $post = JenisKuman::where($where)->delete();
        return response()->json($post);
    }
}
