<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GraphController;
use App\User;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use PhpParser\Node\Expr\New_;

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
    private $api;
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
    public function redirectToFacebookProvider()
    {
       return Socialite::driver('facebook')->scopes([
          " manage_pages", "publish_pages"
        ])->redirect();
    }

    /**
     * Obtain the user information from Facebook.
     *
     * @return void
     */
    public function handleProviderFacebookCallback()
    {
        $user = Socialite::driver('facebook')->user(); // Fetch authenticated user
        try{
            $fb = new Facebook(array('app_id'=>env('FACEBOOK_CLIENT_ID'),'app_secret'=>env('FACEBOOK_CLIENT_SECRET')));
            $page = $fb->get('/me/accounts',$user->token);
            $page = $page->getdecodeBody();
           // $test = $fb->get('/me/pages',$user->token);

        }catch (FacebookSDKException $exception){
            dd($exception);
        }
        dd($page);
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
