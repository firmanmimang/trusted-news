<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\AlertHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function __invoke()
    {
        Auth::logout();

        request()->session()->invalidate();

        request()->session()->regenerateToken();

        AlertHelper::flashSuccess(trans('auth.success.logout'));
        return redirect('/');
    }
}
