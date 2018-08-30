<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

/**
* Redirect the user to the Facebook authentication page.
*
* @return \Illuminate\Http\Response
*/
    public function redirectToFacebookProvider(Request $request)
    {
        session()->put('state', $request->input('state'));
        return Socialite::driver('facebook')->scopes([
          " manage_pages", "publish_pages"
        ])->asPopup()->redirect();
    }

    /**
     * Obtain the user information from Facebook.
     *
     * @return void
     */
    public function handleProviderFacebookCallback()
    {
        $user = Socialite::driver('facebook'); // Fetch authenticated user
        dd($user);

        $user = User::updateOrCreate(
            [
                'email' => $user->email
            ],
            [
                'token' => $user->token,
                'name'  =>  $user->name
            ]
        );

        Auth::login($user, true);

        return redirect()->to('/'); // Redirect to a secure page

    }
}
