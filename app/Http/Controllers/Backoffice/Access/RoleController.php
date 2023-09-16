<?php

namespace App\Http\Controllers\Backoffice\Access;

use App\Helpers\AlertHelper;
use App\Helpers\GuardHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backoffice\Access\RoleStoreRequest;
use App\Http\Requests\Backoffice\Access\RoleUpdateRequest;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        return view('pages.cms.access.role.index', [
            'roles' => Role::with('permissions')
                        ->when(request()->get('search'), function($query){
                            $query->where('name', 'LIKE', '%'.request()->get('search').'%');
                        })
                        ->when(request()->get('column'), function($query){
                            $query->orderBy(request()->get('column'), request()->get('order'));
                        })
                        ->latest()->paginate(request()->size ?? 10)
        ]);
    }

    public function create()
    {
        return view('pages.cms.access.role.create', [
            'permissions' => Permission::where('guard_name', Role::GUARD_CMS)->get(['id', 'name']),
            'guard' => GuardHelper::guard(),
        ]);
    }

    public function store(RoleStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $role = new Role;
            $role->name = $request->name;
            $role->guard_name = $request->guard;
            $role->save();

            $role->givePermissionTo($request->permission);
            DB::commit();

            if( auth('cms')->user()->hasRole(Role::SUPER_ADMIN) || auth('cms')->user()->hasPermissionTo('role management') ){
                AlertHelper::flashSuccess(trans('success.crud_create', ['type' => "Role $role->name"]));
                return redirect()->route('cms.access.role.index');
            }
            else{
                AlertHelper::flashSuccess(trans('success.crud_create', ['type' => "Role $role->name"]));
                return redirect()->route('cms.dashboard');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            Log::error($th);

            AlertHelper::flashError(trans('server.500'));
            return back();
        }
    }

    public function edit(Role $role)
    {
        if($role->name === Role::SUPER_ADMIN) {
            AlertHelper::flashError(trans('failed.prohibited.crud_edit', ['type' => "Role $role->name"]));
            return back();
        }

        return view('pages.cms.access.role.edit', [
            'role'=> $role->load('permissions'),
            'permissions' => Permission::where('guard_name', Role::GUARD_CMS)->get(['id', 'name']),
            'guard' => GuardHelper::guard(),
        ]);
    }

    public function update(RoleUpdateRequest $request, Role $role)
    {
        if($role->name === Role::SUPER_ADMIN) {
            AlertHelper::flashError(trans('failed.prohibited.crud_edit', ['type' => "Role $role->name"]));
            return back();
        }

        try {
            DB::beginTransaction();
            $role->name = $request->name;
            $role->guard_name = $request->guard;
            $role->save();

            $role->syncPermissions($request->permission);
            DB::commit();
            if( auth('cms')->user()->hasRole(Role::SUPER_ADMIN) || auth('cms')->user()->hasPermissionTo('role management') ){
                AlertHelper::flashSuccess(trans('success.crud_update', ['type' => "Role $role->name"]));
                return redirect()->route('cms.access.role.index');
            }
            else{
                AlertHelper::flashSuccess(trans('success.crud_update', ['type' => "Role $role->name"]));
                return redirect()->route('cms.dashboard');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            Log::error($th);

            AlertHelper::flashError(trans('server.500'));
            return back();
        }
    }

    public function destroy(Role $role)
    {
        if($role->name === Role::SUPER_ADMIN) {
            AlertHelper::flashError(trans('failed.prohibited.crud_edit', ['type' => "Role $role->name"]));
            return back();
        }

        try {
            DB::beginTransaction();
            $role->delete();
            DB::commit();

            if( auth('cms')->user()->hasRole(Role::SUPER_ADMIN) || auth('cms')->user()->hasPermissionTo('role management') ){
                AlertHelper::flashSuccess(trans('success.crud_delete', ['type' => "Role $role->name"]));
                return redirect()->route('cms.access.role.index');
            }
            else{
                AlertHelper::flashSuccess(trans('success.crud_delete', ['type' => "Role $role->name"]));
                return redirect()->route('cms.dashboard');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            Log::error($th);

            AlertHelper::flashError(trans('server.500'));
            return back();
        }
    }
}
