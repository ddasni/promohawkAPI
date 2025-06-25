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
        $isLote = $this->has('cupons') && is_array($this->input('cupons'));

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            if ($isLote) {
                return [
                    'cupons' => 'required|array|min:1',
                    'cupons.*.codigo' => 'sometimes|string|max:50',
                    'cupons.*.descricao' => 'sometimes|string|max:100',
                    'cupons.*.desconto' => 'sometimes|numeric|min:0',
                    'cupons.*.validade' => 'sometimes|date|after_or_equal:today',
                    'cupons.*.status_cupom' => 'sometimes|string|max:15',
                    'cupons.*.loja.nome' => 'required|string|max:100',
                    'cupons.*.loja.imagem' => 'nullable|string',
                ];
            } else {
                return [
                    'codigo' => 'sometimes|string|max:50',
                    'descricao' => 'sometimes|string|max:100',
                    'desconto' => 'sometimes|numeric|min:0',
                    'validade' => 'sometimes|date|after_or_equal:today',
                    'status_cupom' => 'sometimes|string|max:15',
                    'loja.nome' => 'required|string|max:100',
                    'loja.imagem' => 'nullable|string',
                ];
            }
        }

        if ($isLote) {
            return [
                'cupons' => 'required|array|min:1',
                'cupons.*.codigo' => 'required|string|max:50',
                'cupons.*.descricao' => 'required|string|max:100',
                'cupons.*.desconto' => 'required|numeric|min:0',
                'cupons.*.validade' => 'required|date|after_or_equal:today',
                'cupons.*.status_cupom' => 'sometimes|string|max:15',
                'cupons.*.loja.nome' => 'required|string|max:100',
                'cupons.*.loja.imagem' => 'nullable|string',
            ];
        }

        return [
            'codigo' => 'required|string|max:50',
            'descricao' => 'required|string|max:100',
            'desconto' => 'required|numeric|min:0',
            'validade' => 'required|date|after_or_equal:today',
            'status_cupom' => 'sometimes|string|max:15',
            'loja.nome' => 'required|string|max:100',
            'loja.imagem' => 'nullable|string',
        ];
    }

    /**
     * Mensagens de erro personalizadas para validação.
     */
    public function messages(): array
    {
        return [
            // Mensagens para cupons em lote
            'cupons.required' => 'É necessário enviar ao menos um cupom.',
            'cupons.*.codigo.required' => 'O código é obrigatório para cada cupom.',
            'cupons.*.descricao.required' => 'A descrição é obrigatória para cada cupom.',
            'cupons.*.desconto.required' => 'O desconto é obrigatório para cada cupom.',
            'cupons.*.validade.required' => 'A validade é obrigatória para cada cupom.',
            'cupons.*.loja.nome.required' => 'O nome da loja é obrigatório para cada cupom.',

            // Mensagens para cupom único
            'codigo.required' => 'O código do cupom é obrigatório.',
            'codigo.max' => 'O código pode ter no máximo :max caracteres.',
            'descricao.required' => 'A descrição é obrigatória.',
            'descricao.max' => 'A descrição pode ter no máximo :max caracteres.',
            'desconto.required' => 'O desconto é obrigatório.',
            'desconto.numeric' => 'O desconto deve ser numérico.',
            'validade.required' => 'A validade do cupom é obrigatória.',
            'validade.date' => 'A validade deve ser uma data válida.',
            'validade.after_or_equal' => 'A validade deve ser hoje ou uma data futura.',
            'loja.nome.required' => 'O nome da loja é obrigatório.',
        ];
    }
}
