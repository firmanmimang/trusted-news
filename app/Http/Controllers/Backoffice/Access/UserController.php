<?php

namespace App\Http\Controllers\Backoffice\Access;

use App\Helpers\AlertHelper;
use App\Helpers\ParseUrlHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backoffice\Access\UserStoreRequest;
use App\Http\Requests\Backoffice\Access\UserUpdateRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        return view('pages.cms.access.user.index', [
            'users' => User::with('roles')
                        ->when(request()->get('search'), function($query){
                            $query->where('name', 'LIKE', '%'.request()->get('search').'%');
                        })
                        ->when(request()->get('column'), function($query){
                            $query->orderBy(request()->get('column'), request()->get('order'));
                        })
                        ->latest()->paginate(request()->size ?? 10)
        ]);
    }

    public function stream()
    {
        return response()->stream(function () {
            while (true) {
                echo "event: ping\n";
                $curDate = date(DATE_ISO8601);
                echo 'data: {"time": "' . $curDate . '"}';
                echo "\n\n";

                $user_online = [];

                $seconds = 60;
                $users = Cache::remember('users', $seconds, function () {
                    return User::get();
                });

                foreach($users as $user){
                    if(Cache::has('user-online' . $user->id)){
                        $user_online[] = [
                            'id' => $user->id,
                            'online' => true,
                        ];
                    }
                    else{
                        $user_online[] = [
                            'id' => $user->id,
                            'online' => false,
                        ];
                    }
                }
                echo 'data: {"user_online":' . json_encode($user_online) . '}' . "\n\n";

                ob_flush();
                flush();

                // Break the loop if the client aborted the connection (closed the page)
                if (connection_aborted()) {break;}
                usleep(5000000); // 500ms
            }
        }, 200, [
            'Cache-Control' => 'no-cache',
            'Content-Type' => 'text/event-stream',
        ]);
    }

    public function create()
    {
        return view('pages.cms.access.user.create', [
            'roles' => Role::whereNot('name', Role::SUPER_ADMIN)->where('guard_name', Role::GUARD_CMS)->get(['id', 'name'])
        ]);
    }

    public function store(UserStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->username = $request->username ? $request->username : (new User())->uniqueUsername($request->name);
            $user->password = bcrypt($request->password);
            $user->save();

            $user->assignRole([$request->role]);

            if(request()->hasFile('avatar')) {
                $avatar = ParseUrlHelper::ParseUrl($user->replaceImage($request->file('avatar')));
                $user->image = $avatar;
                $user->save();
            }
            DB::commit();

            AlertHelper::flashSuccess(trans('success.crud_create', ['type' => "User $user->name"]));
            return redirect()->route('cms.access.user.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            Log::error($th);

            AlertHelper::flashError(trans('server.500'));
            return back();
        }
    }

    public function edit(User $user)
    {
        if ($user->email === User::SUPER_ADMIN_EMAIL){
            AlertHelper::flashError(trans('failed.prohibited.crud_edit', ['type' => "User $user->name"]));
            return back();
        }

        return view('pages.cms.access.user.edit', [
            'user' => $user->load('roles'),
            'roles' => Role::whereNot('name', Role::SUPER_ADMIN)->where('guard_name', Role::GUARD_CMS)->get(['id', 'name'])
        ]);
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        if ($user->username === User::SUPER_ADMIN_EMAIL){
            AlertHelper::flashError(trans('failed.prohibited.crud_update', ['type' => "User $user->name"]));
            return back();
        }

        try {
            DB::beginTransaction();
            $user->name = $request->name;
            $user->username = $request->username ? $request->username : (new User())->uniqueUsername($request->name);
            if($request->password) $user->password = bcrypt($request->password);
            $user->save();

            $user->syncRoles([$request->role]);

            if(request()->hasFile('avatar')) {
                $avatar = ParseUrlHelper::ParseUrl($user->replaceImage($request->file('avatar')));
                $user->image = $avatar;
                $user->save();
            }
            DB::commit();

            AlertHelper::flashSuccess(trans('success.crud_update', ['type' => "User $user->name"]));
            return redirect()->route('cms.access.user.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            Log::error($th);

            AlertHelper::flashError(trans('server.500'));
            return back();
        }
    }

    public function destroy(User $user)
    {
        if ($user->email === User::SUPER_ADMIN_EMAIL){
            AlertHelper::flashError(trans('failed.prohibited.crud_delete', ['type' => "User $user->name"]));
            return back();
        }

        try {
            DB::beginTransaction();
            $user->deleteImage();
            $user->delete();
            DB::commit();

            AlertHelper::flashSuccess(trans('success.crud_delete', ['type' => "User $user->name"]));
            return redirect()->route('cms.access.user.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            Log::error($th);

            AlertHelper::flashError(trans('server.500'));
            return back();
        }
    }
}
