<?php

namespace App\Http\Controllers\Backoffice\Profile;

use App\Helpers\AlertHelper;
use App\Helpers\ParseUrlHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backoffice\Profile\ProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('pages.cms.profile.edit', []);
    }

    public function update(ProfileRequest $request)
    {
        try {
            DB::beginTransaction();
            $user = auth('cms')->user();
            $user->name = $request->name;
            $user->username = $request->username;
            $user->save();
            if(request()->hasFile('avatar')) {
                $avatar = ParseUrlHelper::ParseUrl($user->replaceImage($request->file('avatar')));
                $user->image = $avatar;
                $user->save();
            }
            DB::commit();

            AlertHelper::flashSuccess(trans('success.crud_update', ['type' => "Profile"]));
            return back()->with('alert', ['type' => AlertHelper::ALERT_SUCCESS, 'message' => trans('success.crud_delete', ['type' => "Profile"])]);
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            Log::error($th);

            AlertHelper::flashError(trans('server.500'));
            return back();
        }
    }
}
