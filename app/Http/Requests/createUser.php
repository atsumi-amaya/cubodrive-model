<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class createUser extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'username'=>'required|regex:/^\S*$/u',
            'email'=>'required|unique:users,email',
            'password'=>'required',
        ];
    }

    public function messages()
    {
        return [
            'email.required'=>'inserte email',
            'email.unique'=>'email ya registrado',
            'username.required'=>'inserte nombre de usuario',
            'username.regex'=>'no inserte nombre con espacios',
            'password.required'=>'inserte contraseña',
        ];
    }
}
