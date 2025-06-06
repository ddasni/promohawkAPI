<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CupomRequest extends FormRequest
{
    /**
     * Determina se o cupom está autorizado a fazer esta requisição.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Manipula falhas de validação e retorna uma resposta JSON com os erros.
     */
    protected function failedValidation(Validator $validator) 
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'erros' => $validator->errors(),
        ], 422));
    }

    /**
     * Regras de validação para criação e atualização de cupons.
     */
    public function rules(): array
    {
        $cupomID = $this->route('id');
        $cupomUpdate = $this->isMethod('put') || $this->isMethod('patch');

        if ($cupomUpdate) {
            return [
                'loja_id'       => 'sometimes|exists:loja,id',
                'codigo'        => 'sometimes|string|max:50',
                'descricao'     => 'sometimes|string|max:100',
                'desconto'      => 'sometimes|numeric|min:0',
                'validade'      => 'sometimes|date|after_or_equal:today',
                'status_cupom'  => 'sometimes|string|max:15',
            ];
        }

        return [
            'loja_id'       => 'required|exists:loja,id',
            'codigo'        => 'required|string|max:50',
            'descricao'     => 'required|string|max:100',
            'desconto'      => 'required|numeric|min:0',
            'validade'      => 'required|datetime|after_or_equal:today',
            'status_cupom'  => 'sometimes|string|max:15',
        ];
    }

    /**
     * Mensagens de erro personalizadas para validação.
     */
    public function messages(): array
    {
        return [
            'loja_id.required'         => 'O campo loja_id é obrigatório.',
            'loja_id.exists'           => 'Loja não encontrada.',

            'codigo.required'          => 'O código do cupom é obrigatório.',
            'codigo.string'            => 'O código deve ser uma string.',
            'codigo.max'               => 'O código pode ter no máximo :max caracteres.',

            'descricao.required'       => 'A descrição é obrigatŕia.',
            'descricao.string'         => 'A descrição deve ser uma string.',
            'descricao.max'            => 'A descrição deve ter no máximo :max caracteres.',

            'desconto.required'        => 'O desconto é obrigatório.',
            'desconto.numeric'         => 'O desconto deve ser um número.',
            'desconto.min'             => 'O desconto não pode ser negativo.',

            'validade.required'        => 'A validade do cupom é obrigatória.',
            'validade.date'            => 'Data de validade inválida.',
            'validade.after_or_equal'  => 'A validade deve ser hoje ou uma data futura.',
        ];
    }
}
