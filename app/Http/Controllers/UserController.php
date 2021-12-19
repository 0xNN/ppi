<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the users
     *
     * @param  \App\Models\User  $model
     * @return \Illuminate\View\View
     */
    
    
    public function index(Request $request, User $model)
    {
        if ($request->ajax()) {
            $data = $model->all();
            return datatables()->of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $button = '<div class="btn-group btn-group-sm" role="group">';
                        $button .= '<button type="button" name="delete" id="'.$row->id.'" class="delete btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>';
                        $button .= '</div>';
                        if($row->role == 1)
                        {
                            return 'User Admin';
                        } else {
                            return $button;
                        }
                    })
                    ->editColumn('role', function($row) {
                        if($row->role == 1) {
                            return '<div class="badge badge-info">Admin</div>';
                        } 
                        if($row->role == 0) {
                            return '<div class="badge badge-danger">User</div>';
                        }
                    })
                    ->rawColumns(['action','role'])
                    ->make(true);
        }

        return view('users.index');
    }

    public function store(Request $request)
    {
        $id = $request->id;

        if($request->role == "-1") {
            return response()->json([
                'code' => 201,
                'message' => 'Level belum dipilih!'
            ], 201);
        }

        if($request->name == "" || $request->name == null) {
            return response()->json([
                'code' => 201,
                'message' => 'Nama tidak boleh kosong!'
            ], 201);
        }

        if($request->email == "" || $request->email == null) {
            return response()->json([
                'code' => 201,
                'message' => 'Username tidak boleh kosong!'
            ], 201);
        }

        if($request->password == "" || $request->password == null) {
            return response()->json([
                'code' => 201,
                'message' => 'Password tidak boleh kosong!'
            ], 201);
        }
        
        $user = User::updateOrCreate(['id' => $id],[
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return response()->json([
            'code' => 200,
            'data' => $user
        ],200);
    }

    public function update($id)
    {
        $post = User::find($id);
        $post->email_verified_at = now();
        $post->save();

        return response($post);
    }

    public function destroy($id)
    {
        $post = User::where('id', $id)->delete();

        return response()->json($post);
    }
}
