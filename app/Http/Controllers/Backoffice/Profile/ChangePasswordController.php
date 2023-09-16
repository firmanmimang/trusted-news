<?php

namespace App\Http\Controllers\Backoffice\Profile;

use App\Helpers\AlertHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backoffice\Profile\ChangePasswordRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ChangePasswordController extends Controller
{
    public function edit()
    {
        return view('pages.cms.profile.change-password', []);
    }

    public function update(ChangePasswordRequest $request)
    {
        // if (!Hash::check($request->old_password, auth('cms')->user()->password)) { return back()->withErrors(['old_password' => trans('auth.failed')]); }
        try {
            $user = auth('cms')->user();
            DB::beginTransaction();
            $user->password = bcrypt($request->new_password);
            $user->save();
            DB::commit();
        
            // Auto logout user yang diganti passwordnya
            // Get the user's sessions from the sessions table
            $userSessions = DB::table('sessions')
            ->where('user_id', $user->id)
            ->get();
            
            // Iterate over the user's sessions and invalidate them
            foreach ($userSessions as $session) {
                Session::getHandler()->destroy($session->id);
            }
            
            // Log out the user from the current session if the current session is the changed password user.
            if ($user->id == Auth::user()->id) {
                // Auth::logoutOtherDevices($oldPassword);
                Auth::logout();
            }

            AlertHelper::flashSuccess(trans('success.change_password', ['user' => $user->name]));
            return redirect()->route('cms.dashboard')->with('alert', ['type' => AlertHelper::ALERT_SUCCESS, 'message' => trans('success.change_password', ['user' => $user->name])]);
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            Log::error($th);

            AlertHelper::flashError(trans('server.500'));
            return back();
        }
    }
}
