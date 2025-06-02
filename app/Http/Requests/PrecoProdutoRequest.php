<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PrecoProdutoRequest extends FormRequest
{
    /**
     * Autoriza o envio da requisição.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Pré-processa os dados antes da validação.
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'preco' => str_replace(',', '.', $this->preco),
            'valor_parcela' => str_replace(',', '.', $this->valor_parcela),
        ]);
    }

    /**
     * Manipula falha de validação retornando JSON com os erros.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'erros' => $validator->errors(),
        ], 422));
    }

    /**
     * Regras de validação da requisição.
     */
    public function rules(): array
    {
        $isUpdate = $this->isMethod('put') || $this->isMethod('patch');

        if ($isUpdate) {
            return [
                'produto_id' => 'sometimes|exists:produtos,id',
                'loja_id' => 'sometimes|exists:lojas,id',
                'preco' => 'sometimes|numeric|min:0',
                'forma_pagamento' => 'nullable|string|max:50',
                'parcelas' => 'nullable|integer|min:1',
                'valor_parcela' => 'nullable|numeric|min:0',
            ];
        }

        return [
            'produto_id' => 'required|exists:produtos,id',
            'loja_id' => 'required|exists:lojas,id',
            'preco' => 'required|numeric|min:0',
            'forma_pagamento' => 'nullable|string|max:50',
            'parcelas' => 'nullable|integer|min:1',
            'valor_parcela' => 'nullable|numeric|min:0',
        ];
    }

    /**
     * Mensagens personalizadas de erro.
     */
    public function messages(): array
    {
        return [
            'produto_id.required' => 'O campo produto é obrigatório.',
            'produto_id.exists' => 'O produto informado não existe.',
            'loja_id.required' => 'O campo loja é obrigatório.',
            'loja_id.exists' => 'A loja informada não existe.',
            'preco.required' => 'O campo preço é obrigatório.',
            'preco.numeric' => 'O preço deve ser um valor numérico.',
            'forma_pagamento.max' => 'A forma de pagamento não pode ter mais que :max caracteres.',
            'parcelas.integer' => 'O número de parcelas deve ser um número inteiro.',
            'parcelas.min' => 'O número mínimo de parcelas deve ser 1.',
            'valor_parcela.numeric' => 'O valor da parcela deve ser um número.',
        ];
    }
}

