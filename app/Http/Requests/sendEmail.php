<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class sendEmail extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email'=>'required|exists:users',
        ];
    }
    public function messages()
    {
        return [
            'email.exists'=>'email no registrado',
        ];
    }
}
