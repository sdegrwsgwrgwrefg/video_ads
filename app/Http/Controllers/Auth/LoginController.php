<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        // $this->middleware('guest:admin')->except('logout');
    }

    public function showAdminLoginForm() {
        return view('auth.adminLogin', ['url' => 'admin']);
    }

    public function postAdminLoginForm(Request $request) {


        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        $login_type = filter_var($request->input('email'), FILTER_VALIDATE_EMAIL ) 
        ? 'email' 
        : 'username';

        $request->merge([
            $login_type => $request->input('email')
        ]);

        if(Auth::guard('admin')->attempt(
            // $request->only($login_type, 'password')
            [
                'email' => $request->email, 
                'password' => $request->password
            ]
            , $request->get('remember'))) {
            return redirect()->intended('/admin');
        }
        return back()->withInput($request->only('email', 'remember'));
    }

}
