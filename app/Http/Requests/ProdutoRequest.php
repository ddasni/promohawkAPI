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
                'nome' => 'sometimes',
                'descricao' => 'sometimes',
                'imagem' => 'sometimes',
                'link' => 'sometimes',
                'status_produto' => 'sometimes|in:ativo,inativo',
            ];
        }

        //se não
        return [
            'nome' => 'required|string|max:200',
            'descricao' => 'nullable|string|max:200',
            'preco' => 'required|numeric|min:0',
            'imagem' => 'required|string',
            'link' => 'required|string',
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
            'nome.required' => 'Campo nome do produto é obrigatório!',
            'nome.max' => 'Excedeu mais de :max caracteres no campo nome!',
            'descricao.max' => 'Excedeu mais de :max caracteres no campo descrição!',
            'preco.required' => 'Campo preco do produto é obrigatório!',
            'imagem.required' => 'Campo imagem do produto é obrigatório!',
            'link.required' => 'Campo url do produto é obrigatório!',
        ];
    }
}
