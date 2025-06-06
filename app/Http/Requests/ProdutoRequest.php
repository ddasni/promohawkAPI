<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProdutoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    protected function prepareForValidation()
    {
        $this->merge([
            'preco' => str_replace(',', '.', $this->preco),
        ]);
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
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
      
        // Recuperar o id do usuario enviado na URL
        $produtoID = $this->route('id');

        // Verifica se é um metodo put
        $produtoUpdate = $this->isMethod('put') || $this->isMethod('patch');

        // se for put (atualizar)
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            return [
                'nome'              => 'sometimes|string|max:200',
                'descricao'         => 'sometimes|nullable|string|max:200',
                'forma_pagamento'   => 'nullable|string|max:50',
                'parcelas'          => 'nullable|integer|min:1',
                'valor_parcela'     => 'nullable|numeric|min:0',
                'link'              => 'sometimes|string',
                'status_produto'    => 'sometimes|in:ativo,inativo',
                'imagem'            => 'sometimes|string',
                'imagens'           => 'nullable|array',
                'imagens.*'         => 'string', // cada item do array deve ser uma string (base64 ou URL)
            ];
        }

        //se não
        return [
            'nome'              => 'required|string|max:200',
            'descricao'         => 'nullable|string|max:200',
            'preco'             => 'required|numeric|min:0',
            'forma_pagamento'   => 'nullable|string|max:50',
            'parcelas'          => 'nullable|integer|min:1',
            'valor_parcela'     => 'nullable|numeric|min:0',
            'imagem'            => 'required|string',
            'imagens'           => 'nullable|array',
            'imagens.*'         => 'string',
            'link'              => 'required|string',
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
            'nome.required'         => 'Campo nome do produto é obrigatório!',
            'nome.max'              => 'Excedeu mais de :max caracteres no campo nome!',
            'descricao.max'         => 'Excedeu mais de :max caracteres no campo descrição!',
            'preco.required'        => 'Campo preco do produto é obrigatório!',
            'imagem.required'       => 'Campo imagem do produto é obrigatório!',
            'link.required'         => 'Campo url do produto é obrigatório!',
            'forma_pagamento.max'   => 'O campo forma de pagamento não pode ter mais de :max caracteres.',
            'parcelas.integer'      => 'O número de parcelas deve ser um número inteiro.',
            'valor_parcela.numeric' => 'O valor da parcela deve ser um número.',
            'imagens.array'         => 'As imagens devem estar em um array.',
            'imagens.*.string'      => 'Cada imagem deve ser uma string (base64 ou URL).',
        ];
    }
}
