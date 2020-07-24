<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ImageRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        $rules = [
            'type' => 'required|string|in:avatar,topic'
        ];

        if ($this->type == 'avatar') {
            $rules['image'] = 'mimes:jpeg,bmb,png,gif|dimensions:min_width=350,min_height=350';
        } else {
            $rules['image'] = 'mimes:jpeg,bmb,png,gif';
        }
        return $rules;
    }

    public function messages() {
        return [
            'image.dimensions'=>'用户头像不得低于350px',
        ];

    }
}
