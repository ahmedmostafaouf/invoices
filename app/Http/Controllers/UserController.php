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
        try {
            $request_data=$request->except(['password']);
            $request_data['password']=bcrypt($request->password);
            Db::beginTransaction();
            $user=User::create($request_data);
            $user->assignRole($request->input('roles_name'));
            DB::commit();
            return redirect()->route('users.index')->with(['success'=>'تم الاضافة بنجاح']);
        }catch (\Exception $ex){
             DB::rollBack();
            return redirect()->route('users.index')->with(['error'=>'حدث خطأما']);
        }

    }
    public function edit($id){
        try {
            $users=User::findOrFail($id);
            $roles=Role::pluck('name','name')->all();
            $userRole = $users->roles->pluck('name','name')->all();
            return view('users.edit_user',compact('users','roles','userRole'));
        }catch (\Exception $ex){
            return redirect()->route('users.index')->with(['error'=>'حدث خطأما']);
        }

    }
    public function update(UserRequest $request,$id){
        try {
            $users=User::findOrFail($id);
            $request_date = $request->except(['password']);
            if($request->has('password')){
                $request_date['password'] = bcrypt($request->password);
            }
            DB::table('model_has_roles')->where('model_id',$id)->delete();
            $users->assignRole($request->input('roles_name'));
            $users->update($request_date);

            return redirect()->route('users.index')->with(['success'=>'تم التعديل بنجاح']);
        }catch (\Exception $ex){
            return redirect()->route('users.index')->with(['error'=>'حدث خطأما']);
        }

    }
    public function destroy($id){
        try {
            $users=User::findOrFail($id);
            $users->delete();
            DB::table('model_has_roles')->where('model_id',$id)->delete();
            return redirect()->route('users.index')->with(['success'=>'تم الحذف بنجاح']);
        }catch (\Exception $ex){
            return redirect()->route('users.index')->with(['error'=>'حدث خطأما']);

        }

    }
}

