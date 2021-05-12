<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

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
    }

    public function index(){

        return view('admin.login');
    }

    public function authenticate(Request $request){

        $data = $request->only([
            'email',
            'password'
        ]);

        $validate = $this->validator($data);

        $remember = $request->input('remember', false);

        if($validate->fails()){
            return redirect()->route('login')
                ->withErrors($validate)
                ->withInput();
        }

        if(Auth::attempt($data, $remember)){
            return redirect()->route('admin');
        } else {
            $validate->errors()->add('password', 'Usuario e/ou senha incorretos');

            return redirect()->route('login')
                ->withErrors($validate)
                ->withInput();
        }
    }

    public function validator(array $data){
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:100'],
            'password' => ['required', 'string','min:4']
        ]);
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('login');
    }
}
