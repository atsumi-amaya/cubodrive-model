<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class upload extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'file' => 'required|max:100000',
            //'file' => 'clamav',
        ];
    }
    public function messages()
    {
        return [
            'file.required'=>'suba un archivo',
            'file.max'=>'archivo muy grande',
            //'file.clamav'=> 'archivo infectado!!!'
        ];
    }
}