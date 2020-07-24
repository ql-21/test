<?php

namespace App\Http\Requests\Api;


class UsersRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()){
            case 'POST':
                $rules =  [
                    'name'=>'required|between:3,25|regex:/^[A-Za-z0-9\-\_]+$/|unique:users,name',
                    'password'=>'required|alpha_dash|min:6',
                    'verification_key'=>'required|string',
                    'verification_code'=>'required|string',
                ];
                break;

            case 'PATCH':
                $userId=auth('api')->id();
                $rules = [
                    'name' => 'between:3,25|regex:/^[A-Za-z0-9\-\_]+$/|unique:users,name,' .$userId,
                    'nickname' => 'required|alpha_dash|unique:users,nickname,'.$userId,
                    'email'=>'email|unique:users,email,'.$userId,
                    'sex'=>'numeric|between:0,2',
                    'introduction' => 'max:80',
                    'avatar_image_id' => 'exists:images,id,type,avatar,user_id,'.$userId,
                ];
                break;

        }


        return $rules;
    }

    public function attributes()
    {
        return [
            'verification_key'=>'短信验证码key',
            'verification_code'=>'短信验证码',
            'sex'=>'性别',
            'nickname'=>'昵称'
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => '用户名已被占用，请重新填写',
            'name.regex' => '用户名只支持英文、数字、横杆和下划线。',
            'name.between' => '用户名必须介于 3 - 25 个字符之间。',
            'name.required' => '用户名不能为空。',
        ];
    }
}
