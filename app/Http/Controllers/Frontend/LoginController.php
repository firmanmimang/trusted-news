<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\AlertHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\LoginStoreRequest;
use App\Models\Account;
use App\Models\User;
use Google_Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    public function __invoke()
    {
        return view('pages.frontend.login');
    }

    public function store(LoginStoreRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        AlertHelper::flashSuccess(trans('auth.success.login', ['user' => auth()->user()->name]));
        if($request->has('in')){
            return redirect($request->get('in'));
        }else{
            return redirect()->intended(route('home'));
        }
    }

    public function socialiteRedirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function socialiteCallback($provider)
    {
        try {
            $user_socialite = Socialite::driver($provider)->stateless()->user();
            $user           = User::where('email', $user_socialite->getEmail())->first();
            
            if($user != null){
                DB::beginTransaction();
                if($provider == Account::GOOGLE){
                    $account = $user->accounts()->firstOrCreate(
                        ['provider_type' => $provider, 'provider_id' => $user_socialite->getId()],
                        [
                            'detail' => [
                                'token'             => $user_socialite->token,
                                'verified_email'    => $user_socialite->user['verified_email'],
                                'locale'            => $user_socialite->user['locale'],
                                'nickname'          => $user_socialite->getNickname(),
                            ],
                        ]
                    );
    
                    // update token from google
                    $accountDetails = $account->detail;
                    $accountDetails['token'] = $user_socialite->token;
                    $accountDetails['locale'] = $user_socialite->user['locale'];
                    $accountDetails['nickname'] = $user_socialite->getNickname();
                    $account->detail = $accountDetails;
    
                    $account->save();
                }
                if($provider == Account::GITHUB){
                    $account = $user->accounts()->firstOrCreate(
                        ['provider_type' => $provider, 'provider_id' => $user_socialite->getId()],
                        [
                            'detail' => [
                                'token'             => $user_socialite->token,
                                'nickname'          => $user_socialite->getNickname(),
                            ],
                        ]
                    );
    
                    $accountDetails = $account->detail;
                    $accountDetails['token'] = $user_socialite->token;
                    $accountDetails['nickname'] = $user_socialite->getNickname();
                    $account->detail = $accountDetails;
    
                    $account->save();
                }
                DB::commit();

                Auth::loginUsingId($user->id);

                request()->session()->regenerate();
                AlertHelper::flashSuccess(trans('auth.success.login', ['user' => auth()->user()->name]));
                return redirect()->intended('/');
            }else{
                DB::beginTransaction();
                $user = User::Create([
                    'email'             => $user_socialite->getEmail(),
                    'name'              => $user_socialite->getName(),
                    'image'             => ($provider == Account::GITHUB ) ? $user_socialite->getAvatar() : null,
                    'username'          => (new User())->uniqueUsername($user_socialite->getName()),
                    'email_verified_at' => isset($user_socialite->user['verified_email']) ? ($user_socialite->user['verified_email'] == 'true'? now() : null) : null,
                ]);

                if($provider == Account::GOOGLE){
                    $user->accounts()->create([
                        'provider_type' => $provider,
                        'provider_id' => $user_socialite->getId(),
                        'detail' => [
                            'token'             => $user_socialite->token,
                            'verified_email'    => $user_socialite->user['verified_email'],
                            'locale'            => $user_socialite->user['locale'],
                            'nickname'          => $user_socialite->getNickname(),
                        ],
                    ]);
                }
                if($provider == Account::GITHUB){
                    $user->accounts()->create([
                        'provider_type' => $provider,
                        'provider_id' => $user_socialite->getId(),
                        'detail' => [
                            'token'             => $user_socialite->token,
                            'nickname'          => $user_socialite->getNickname(),
                        ],
                    ]);
                }

                $user->givePermissionTo(['comment', 'edit profile', 'change password']);
                
                DB::commit();

                Auth::loginUsingId($user->id);

                request()->session()->regenerate();
                AlertHelper::flashSuccess(trans('auth.success.login', ['user' => auth()->user()->name]));
                return redirect()->intended('/');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            // throw $e;
            AlertHelper::flashError(trans('server.500'));
            return back();
        }
    }

    public function googleOneTapLogin(Request $request)
    {
        if ($_COOKIE['g_csrf_token'] !== $request->input('g_csrf_token')) {
            // invalid csrf token
            AlertHelper::flashError('Invalid csrf token.');
            return back();
        }
        
        $idToken = $request->input('credential'); 
            
        $client = new Google_Client([
            'client_id' => env('GOOGLE_CLIENT_ID')
        ]);
        
        $payload = $client->verifyIdToken($idToken);
        
        if (!$payload) {
            // Invalid ID token
            AlertHelper::flashError('Invalid id token.');
            return back();
        }

        try {
            $user = User::where('email', $payload['email'])->first();
            
            if($user != null){
                DB::beginTransaction();
                $user->accounts()->firstOrCreate(
                    ['provider_type' => Account::GOOGLE, 'provider_id' => $payload['sub']],
                    [
                        'detail' => [
                            'verified_email'    => $payload['email_verified'],
                            'locale'            => $payload['locale'],
                        ],
                    ]
                );
                DB::commit();
    
                Auth::loginUsingId($user->id);
    
                request()->session()->regenerate();
                AlertHelper::flashSuccess(trans('auth.success.login', ['user' => auth()->user()->name]));
                return redirect()->intended('/');
            }else{
                DB::beginTransaction();
                $user = User::Create([
                    'email'             => $payload['email'],
                    'name'              => $payload['name'],
                    'username'          => (new User())->uniqueUsername($payload['name']),
                    'email_verified_at' => $payload['email_verified'] == 'true' ? now() : null,
                ]);
    
                $user->accounts()->create([
                    'provider_type' => Account::GOOGLE,
                    'provider_id' => $payload['sub'],
                    'detail' => [
                        'verified_email'    => $payload['email_verified'],
                        'locale'            => $payload['locale'],
                    ],
                ]);
    
                $user->givePermissionTo(['comment', 'edit profile', 'change password']);
                
                DB::commit();
    
                Auth::loginUsingId($user->id);
    
                request()->session()->regenerate();
                AlertHelper::flashSuccess(trans('auth.success.login', ['user' => auth()->user()->name]));
                return redirect()->intended('/');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            AlertHelper::flashError(trans('server.500'));
            return back();
        }

    }
}
