<?php

namespace App\Http\Controllers;

use App\Http\Requests\RolesRequest;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(){
        $roles=Role::all();
        return view('roles.index',compact('roles'));
    }
    public function create(){
        $permission=Permission::all();
        return view('roles.create_roles',compact('permission'));
    }
    public function store(RolesRequest $request){
        $role =Role::create(['name'=>$request->name]);
        $role->syncPermissions($request->permission);
        return redirect()->route('roles.index')->with(['success'=>'تم الاضافة بنجاح']);
    }
    public function show($id){
        $role=Role::findOrFail($id);
        $rolePermissions=Permission::join('role_has_permissions','role_has_permissions.permission_id','=','permissions.id')->where('role_has_permissions.role_id','=',$id)->get();
           return view('roles.show',compact('role','rolePermissions'));
    }
    public function edit($id){
        $role=Role::findOrFail($id);
        if(! $role){
            return redirect()->route('role.index')->with(['error'=>' هذا الصلاحيه ربما تكون اتحذفت من الموقع']);
        }
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();
        $permissions= Permission::all();
         return view('roles.edit_roles',compact('role','rolePermissions','permissions'));

    }
    public function update(RolesRequest  $request ,$id){
        $role=Role::findOrFail($id);
        if(! $role){
            return redirect()->route('role.index')->with(['error'=>' هذا الصلاحيه ربما تكون اتحذفت من الموقع']);
        }
        $role->update(['name'=>$request->name]);
        $role->syncPermissions($request->permission);
        return  redirect()->route('roles.index')->with(['success'=>"تم التعديل بنجاج"]);
    }

    public function destroy($id){
        $role=Role::findOrFail($id);
        $role->delete();
        return redirect()->route('roles.index')->with(['success'=>'تم الحذف بنجاح']);

    }
}
