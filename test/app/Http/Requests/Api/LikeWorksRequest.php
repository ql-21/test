<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class LikeWorksRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        switch ($this->method()){
            case 'GET':
                break;
            case 'POST':
                $rules=[
                    'work_id'=>'required|numeric|exists:works,id'
                ];
                break;
            case 'DELETE':
                break;
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'work_id'=>'作品id'
        ];
    }
}
