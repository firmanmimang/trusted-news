<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\AlertHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\RegisterStoreRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function __invoke()
    {
        return view('pages.frontend.register');
    }

    public function store(RegisterStoreRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if(!$user){
            $user = User::where('email', $request->email)->create([
                'name' => $request->nama,
                'username' => (new User())->uniqueUsername($request->nama),
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $user->givePermissionTo(['comment', 'edit profile', 'change password']);

        } else {
            $user->update([
                'name' => $request->nama,
                'password' => Hash::make($request->password),
            ]);
        }

        Auth::login($user);

        $request->session()->regenerate();

        AlertHelper::flashSuccess(trans('auth.success.register', ['user' => auth()->user()->name]));
        if($request->has('in')){
            return redirect($request->get('in'));
        }else{
            return redirect()->intended(route('home'));
        }
    }
}
