<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // habilitar uma autorização. padrão false
        return true;
    }

    protected function failedValidation(Validator $validator) 
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'erros' => $validator->errors(),
        ], 422));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Recuperar o id do usuario enviado na URL
        $userID = $this->route('id');


        return [
            'name' => 'required',
            'email' => 'required | email | unique:users,email,'. ($userID ? $userID->id : null),
            'password' => 'required | min:6'
        ];
    }
    public function messages():array
    {
        return[
            'name.required' => 'Campo nome é obrigatório!',
            'email.required' => 'Campo e-mail é obrigatório!',
            'email.email' => 'Necessario enviar e-mail válido!',
            'email.unique' => 'O e-mail já está cadastrado!',
            'password.required' => 'Campo senha é obrigatório!',
            'password.min' => 'Senha com no mínimo :min caracteres!',
        ];
    }
}
