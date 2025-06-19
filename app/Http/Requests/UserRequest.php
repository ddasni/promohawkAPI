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


    /**
     * Manipular falha de validação e retornar uma resposta JSON com os erros de validação.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator O objeto de validação que contém os erros de validação.
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator) 
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'erros' => $validator->errors(),
        ], 422));
    }

    /**
     * Retorna as regras de validação para os dados do usuário.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Recuperar o id do usuario enviado na URL
        $userID = $this->route('id');

        // Verifica se é um metodo put
        $userUpdate = $this->isMethod('put') || $this->isMethod('patch');

        // se for um metodo put vai usar essa regra
        if ($userUpdate) {
            return [
                'username' => 'sometimes|string|max:30',
                'nome' => 'sometimes|string|max:100',
                'telefone' => 'sometimes|string|max:15',
                'email' => 'sometimes|email|max:100|unique:users,email,' . ($userID ? $userID->id : 'NULL'),
                'password' => 'sometimes|string|min:6',
            ];
        }

        // se não (criação)
        return [
            'username' => 'required|string|max:30|unique:users',
            'nome' => 'required|string|max:100',
            'telefone' => 'required|string|max:15',
            'email' => 'required|email|max:100|unique:users',
            'password' => 'required|string|min:6',
        ];
    }


    /**
     * Retorna as mensagens de erro personalizadas para as regras de validação.
     *
     * @return array
     */
    public function messages():array
    {
        return[
            'username.required' => 'Campo username é obrigatório!',
            'username.unique' => 'Este username já está em uso!',
            'username.max' => 'Username não pode exceder 30 caracteres!',
            'nome.required' => 'Campo nome é obrigatório!',
            'nome.max' => 'Nome não pode exceder 100 caracteres!',
            'telefone.required' => 'Campo telefone é obrigatório!',
            'telefone.max' => 'Telefone não pode exceder 15 caracteres!',
            'email.required' => 'Campo e-mail é obrigatório!',
            'email.email' => 'Necessario enviar e-mail válido!',
            'email.unique' => 'O e-mail já está cadastrado!',
            'email.max' => 'E-mail não pode exceder 100 caracteres!',
            'password.required' => 'Campo senha é obrigatório!',
            'password.min' => 'Senha com no mínimo :min caracteres!',
            'password.string' => 'A senha deve ser uma string!',
        ];
    }
}