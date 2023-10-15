<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class invite extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            //'username'=>'regex:/^\S*$/u',
            'email'=>'unique:users,email'
        ];
    }

    public function messages()
    {
        return [
            'email.unique'=>'email ya registrado',
            //'username.regex'=>'no inserte nombre con espacio',
        ];
    }
}
