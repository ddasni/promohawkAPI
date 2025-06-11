<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class FavoritoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'erros' => $validator->errors(),
        ], 422));
    }

    public function rules(): array
    {
        return [
            'usuario_id' => 'required|exists:users,id',
            'produto_id' => 'required|exists:produto,id',
        ];
    }

    public function messages(): array
    {
        return [
            'usuario_id.required' => 'O campo usuário é obrigatório.',
            'usuario_id.exists' => 'Usuário não encontrado.',
            'produto_id.required' => 'O campo produto é obrigatório.',
            'produto_id.exists' => 'Produto não encontrado.',
        ];
    }
}