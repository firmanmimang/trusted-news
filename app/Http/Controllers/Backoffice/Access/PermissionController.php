<?php

namespace App\Http\Controllers\Backoffice\Access;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        return view('pages.cms.access.permission.index', [
            'permissions' => Permission::
                                where('name', 'LIKE', '%'.request()->get('search').'%')
                                ->when(request()->get('column'), function($query){
                                    $query->orderBy(request()->get('column'), request()->get('order'));
                                })
                                ->latest()->paginate(request()->size ?? 10)->appends(request()->all()),
        ]);
    }
}
