<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LojaRequest extends FormRequest
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
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            return [
                'nome' => 'sometimes|string|max:100',
                'imagem' => 'sometimes|string',
            ];
        }

        return [
            'nome' => 'required|string|max:100',
            'imagem' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O nome da loja é obrigatório.',
            'nome.max' => 'O nome da loja deve ter no máximo :max caracteres.',
            'imagem.required' => 'A imagem da loja é obrigatória.'
        ];
    }
}