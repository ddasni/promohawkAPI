<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ReviewRequest extends FormRequest
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
            'produto_id' => 'required|exists:produto,id',
            'usuario_id' => 'required|exists:users,id',
            'avaliacao_produto' => 'required|integer|min:1|max:5',
            'comentario_produto' => 'required|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'produto_id.required' => 'O campo produto_id é obrigatório.',
            'produto_id.exists' => 'O produto especificado não existe.',
            'usuario_id.required' => 'O campo usuario_id é obrigatório.',
            'usuario_id.exists' => 'O usuário especificado não existe.',
            'avaliacao_produto.required' => 'A avaliação é obrigatória.',
            'avaliacao_produto.integer' => 'A avaliação deve ser um número inteiro.',
            'avaliacao_produto.min' => 'A avaliação mínima é 1.',
            'avaliacao_produto.max' => 'A avaliação máxima é 5.',
            'comentario_produto.required' => 'O comentário é obrigatório.',
            'comentario_produto.string' => 'O comentário deve ser um texto.',
            'comentario_produto.max' => 'Comentário muito longo (máx: 1000 caracteres).',
        ];
    }
}