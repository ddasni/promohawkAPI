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


    /**
    * Função para ajustar ou formatar os dados da requisição antes que a validação aconteça.
    */
    protected function prepareForValidation()
    {
        $dados = $this->all();

        if (isset($dados['produtos']) && is_array($dados['produtos'])) {
            foreach ($dados['produtos'] as $index => $produto) {
                if (isset($produto['preco'])) {
                    $dados['produtos'][$index]['preco'] = str_replace(',', '.', $produto['preco']);
                }
                if (isset($produto['valor_parcela'])) {
                    $dados['produtos'][$index]['valor_parcela'] = str_replace(',', '.', $produto['valor_parcela']);
                }
            }
        } else {
            if (isset($dados['preco'])) {
                $dados['preco'] = str_replace(',', '.', $dados['preco']);
            }
            if (isset($dados['valor_parcela'])) {
                $dados['valor_parcela'] = str_replace(',', '.', $dados['valor_parcela']);
            }
        }

        $this->replace($dados);
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
        $isLote = $this->has('produtos') && is_array($this->input('produtos'));

        // PUT/PATCH update
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            if ($isLote) {
                return [
                    'produtos' => 'required|array|min:1',
                    'produtos.*.nome' => 'sometimes|string|max:200',
                    'produtos.*.descricao' => 'sometimes|nullable|string|max:200',
                    'produtos.*.forma_pagamento' => 'nullable|string|max:50',
                    'produtos.*.parcelas' => 'nullable|integer|min:1',
                    'produtos.*.valor_parcela' => 'nullable|numeric|min:0',
                    'produtos.*.link' => 'sometimes|string',
                    'produtos.*.status_produto' => 'sometimes|in:ativo,inativo',
                    'produtos.*.imagens' => 'nullable|array',
                    'produtos.*.imagens.*' => 'string',
                ];
            } else {
                return [
                    'nome' => 'sometimes|string|max:200',
                    'descricao' => 'sometimes|nullable|string|max:200',
                    'forma_pagamento' => 'nullable|string|max:50',
                    'parcelas' => 'nullable|integer|min:1',
                    'valor_parcela' => 'nullable|numeric|min:0',
                    'link' => 'sometimes|string',
                    'status_produto' => 'sometimes|in:ativo,inativo',
                    'imagens' => 'nullable|array',
                    'imagens.*' => 'string',
                ];
            }
        }

        // POST (criação)
        if ($isLote) {
            return [
                'produtos' => 'required|array|min:1',
                'produtos.*.nome' => 'required|string|max:200',
                'produtos.*.descricao' => 'nullable|string|max:200',
                'produtos.*.preco' => 'required|numeric|min:0',
                'produtos.*.forma_pagamento' => 'nullable|string|max:50',
                'produtos.*.parcelas' => 'nullable|integer|min:1',
                'produtos.*.valor_parcela' => 'nullable|numeric|min:0',
                'produtos.*.link' => 'required|string',
                'produtos.*.status_produto' => 'in:ativo,inativo',
                'produtos.*.imagens' => 'nullable|array',
                'produtos.*.imagens.*' => 'string',
            ];
        }

        return [
            'nome' => 'required|string|max:200',
            'descricao' => 'nullable|string|max:200',
            'preco' => 'required|numeric|min:0',
            'forma_pagamento' => 'nullable|string|max:50',
            'parcelas' => 'nullable|integer|min:1',
            'valor_parcela' => 'nullable|numeric|min:0',
            'imagens' => 'nullable|array',
            'imagens.*' => 'string',
            'link' => 'required|string',
        ];
    }


    /**
     * Retorna as mensagens de erro personalizadas para as regras de validação.
     *
     * @return array
     */
   public function messages(): array
    {
        return [
            // Cadastrar de um unico produto
            'nome.required' => 'Campo nome do produto é obrigatório!',
            'nome.max' => 'Excedeu mais de :max caracteres no campo nome!',
            'descricao.max' => 'Excedeu mais de :max caracteres no campo descrição!',
            'preco.required' => 'Campo preco do produto é obrigatório!',
            'link.required' => 'Campo url do produto é obrigatório!',
            'forma_pagamento.max' => 'O campo forma de pagamento não pode ter mais de :max caracteres.',
            'parcelas.integer' => 'O número de parcelas deve ser um número inteiro.',
            'valor_parcela.numeric' => 'O valor da parcela deve ser um número.',
            'imagens.array' => 'As imagens devem estar em um array.',
            'imagens.*.string' => 'Cada imagem deve ser uma string (base64 ou URL).',

            // Cadastro de um array de produtos
            'produtos.required' => 'É necessário enviar pelo menos um produto.',
            'produtos.*.nome.required' => 'O campo nome é obrigatório para cada produto.',
            'produtos.*.preco.required' => 'O campo preço é obrigatório para cada produto.',
            'produtos.*.preco.numeric' => 'O campo preço deve ser numérico.',
            'produtos.*.link.required' => 'O link é obrigatório para cada produto.',
        ];
    }
}
