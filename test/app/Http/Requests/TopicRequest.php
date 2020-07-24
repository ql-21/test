<?php

namespace App\Http\Requests;

class TopicRequest extends Request
{
    public function rules()
    {
        switch($this->method())
        {
            // CREATE
            case 'POST':
            // UPDATE
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'title'=>'required|min:2',
                    'category_id'=>'required|numeric',
                    'body'=>'required|min:3',
                ];
            }
            case 'GET':
            case 'DELETE':
            default:
            {
                return [];
            }
        }
    }

    public function messages()
    {
        return [
            'title.min'=>'标题最少是2个字符',
            'body.min'=>'内容最少是3个字符'
        ];
    }
}
