<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Query\Builder;
use Illuminate\Auth\SessionGuard;
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

    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'username';
    }

    protected function credentials(Request $request)

    {
        $credentials = $request->only($this->username(), 'password');
        $credentials['suspend'] = 'no';
        return $credentials;
    } 

    public function redirectTo()
 {
    $id = Auth::user()->id;
    Auth::user()->update(['api_token' => str_random(60)]);
    
    if (Auth::user()->role==='admin') {
        return 'admin/';
    }elseif (Auth::user()->role==='staff') {
         return 'staff/';
    }else{
        
        return 'customer/';
    }

 }
}