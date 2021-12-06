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
                        if($row->email_verified_at == null)
                        {
                            $button .= '<button type="button" name="verify" data-id="'.$row->id.'" id="'.$row->id.'" class="verify btn btn-success btn-sm"><i class="fas fa-check"></i></button>';
                        }
                        $button .= '</div>';
                        if($row->is_admin == 1)
                        {
                            return 'User Admin';
                        } else {
                            return $button;
                        }
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        return view('users.index');
    }

    public function store(Request $request)
    {
        $id = $request->id;

        $user = User::updateOrCreate(['id' => $id],[
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json($user);
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
