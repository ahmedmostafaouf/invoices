<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(){
        $users = User::all();
        return view('users.index',compact('users'));
    }
    public function create(){
        $roles=Role::pluck('name','name')->all();
        return view('users.create_user',compact('roles'));
    }
    public function store(UserRequest $request){
       $request_data=$request->except(['password']);
       $request_data['password']=bcrypt($request->password);
       $user=User::create($request_data);
       $user->assignRole($request->input('roles_name'));
       return redirect()->route('users.index')->with(['success'=>'تم الاضافة بنجاح']);
    }
    public function edit($id){
       $users=User::findOrFail($id);
        $roles=Role::pluck('name','name')->all();
        $userRole = $users->roles->pluck('name','name')->all();
        return view('users.edit_user',compact('users','roles','userRole'));
    }
    public function update(UserRequest $request,$id){
        $users=User::findOrFail($id);


         $request_date = $request->except(['password']);
        if($request->has('password')){
            $request_date['password'] = bcrypt($request->password);
        }
        DB::table('model_has_roles')->where('model_id',$id)->delete();
        $users->assignRole($request->input('roles_name'));
        $users->update($request_date);

        return redirect()->route('users.index')->with(['success'=>'تم التعديل بنجاح']);
    }
    public function destroy($id){
        $users=User::findOrFail($id);
        $users->delete();
        DB::table('model_has_roles')->where('model_id',$id)->delete();
        return redirect()->route('users.index')->with(['success'=>'تم الحذف بنجاح']);


    }
}

