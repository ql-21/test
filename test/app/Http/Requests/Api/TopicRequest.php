<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class TopicRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()){
            case 'GET' :{
                $rules=[
//                    'filter.category_id'=>'exists:categories,id'
                ];
                break;
            }
            case 'POST':
            {
                $rules=[
                    'title'=>'required|string|min:2',
                    'body'=>'required|string|min:3',
                    'category_id'=>'required|exists:categories,id',
                ];
                break;
            }
            case 'PATCH':
            {
                $rules=[
                    'title'=>'required|string|min:2',
                    'body'=>'required|string|min:3',
                    'category_id'=>'exists:categories,id',
                ];
                break;
            }

        }
        return $rules;
    }

    public function attributes() {
        return [
            'category_id'=>'话题分类',
            'title'=>'标题',
            'body'=>'话题内容'
        ];
    }
}
