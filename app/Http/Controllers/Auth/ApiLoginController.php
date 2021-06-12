<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use Illuminate\Support\Str;


class ApiLoginController extends Controller
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

    // use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        // $this->middleware('auth:api')->except('userLogin', 'userRegister');
        // $this->middleware('guest:admin')->except('logout');
    }

    // public function postLoginForm(Request $request) {
    //     $this->validate($request, [
    //         'email' => 'required|email',
    //         'password' => 'required|min:6'
    //     ]);

    //     if(Auth::guard('admin')->attempt([
    //         'email' => $request->email, 
    //         'password' => $request->password
    //     ], $request->get('remember'))) {
    //         return redirect()->intended('/admin');
    //     }
    //     return back()->withInput($request->only('email', 'remember'));
    // }

    public function userRegister(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'username' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'phone' => 'required|string|max:255',
        ]);
    
        if ($validator->fails()) {
            return $response['response'] = $validator->messages();
        }

        $data = request(['firstname', 'lastname', 'username', 'email', 'password', 'phone']);

        $data['password'] = Hash::make($request->password);
        $data['remember_token'] = Str::random(10);

        $user = User::create($data);

        $token = $user->createToken('API Token')->accessToken;

        return response([
            'user' => auth()->user(), 
            'token' => $token
        ]);
    }

    public function userLogin(Request $request)
    {
        // $data = $request->validate([
        //     'email' => 'email|required',
        //     'password' => 'required'
        // ]);

        $validator = \Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
           
        ]);
    
        if ($validator->fails()) {
            return $response['response'] = $validator->messages();
        }
        // $data = $request->validate([
        //     'email' => 'required|string|email',
        //     'password' => 'required|string',
        //     'remember_me' => 'boolean'
        // ]);

        $credentials = request(['email', 'password']);

        if (!auth()->attempt($credentials)) {
            return response(['error_message' => 'Incorrect Details. 
            Please try again']);
        }

        $token = auth()->user()->createToken('API Token')->accessToken;

        return response([
            'user' => auth()->user(), 
            'token' => $token
        ]);

    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */ 
    public function logout(Request $request) {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
