<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|between:3,25|regex:/^[A-Za-z0-9\_]+$/|unique:users,name,'.Auth::id(),
            'nickname'=>'required|alpha_dash|unique:users,nickname,'.Auth::id(),
            'email' => 'required|email',
            'introduction'=>'max:80',
            'avatar' => 'mimes:jpeg,bmb,png,gif|dimensions:min_width=350,min_height=350',
        ];
    }

    public function messages()
    {
        return [
            'name.unique'=>'用户名已被占用，请重新填写',
            'name.between' => '用户名必须在3-25字符之间',
            'name.regex'=>'用户名只支持英文，数字，下划线',
            'name.required'=>'用户名不能为空',
            'avatar.dimensions'=>'用户头像不得低于350px',
            'avatar.mimes'=>'头像文件后缀支持:jpeg,bmp,png,gif',
        ];
    }

    public function attributes()
    {
        return [
            'nickname'=>'昵称'
        ];
    }
}
