<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string','regex:/^[A-Za-z0-9\_]+$/', 'max:255','unique:users,name'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'nickname'=>['required','alpha_dash','unique:users,nickname'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'captcha' => ['required','captcha'],
        ],[
            'captcha.required' => '验证码不能为空',
            'captcha.captcha' => '请输入正确的验证码',
            'name.regex'=>'用户名只支持英文，数字，下划线',
            'name.unique'=>'用户名被占用',
            'nickname.unique'=>'昵称被占用',
        ],[
            'nickname'=>'昵称'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user=User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'nickname'=>$data['nickname'],
            'password' => Hash::make($data['password']),
        ]);

        return $user;


    }
}
