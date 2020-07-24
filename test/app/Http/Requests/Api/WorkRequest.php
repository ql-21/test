<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class WorkRequest extends FormRequest
{
    public function rules()
    {
        $rules=[];
        switch ($this->method()){
            case 'GET':
                $rules=[
                ];
                break;

            case 'POST':
                $userId=auth('api')->id();
                $rules=[
                    'title'=>'required|string|min:3',
                    'type'=>'required|numeric|between:1,3',
                    'body'=>'required|string|min:3',
                    'cover_image_id' => 'exists:images,id,type,avatar,user_id,'.$userId,
                    'tags'=>'required|array',
                    'tags.*'=>'required|numeric|exists:tags,id,is_show,1'
                ];
                break;
        }
        return $rules;
    }

    public function attributes()
    {
        return [
            'tags' => '标签',
            'tags.*' => '标签元素'
        ];
    }
}
