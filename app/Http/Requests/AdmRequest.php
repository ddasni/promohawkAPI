<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AdmRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
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


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $admID = $this->route('id');
        $isUpdate = $this->isMethod('put') || $this->isMethod('patch');

        if ($isUpdate) {
            return [
                'nome' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:adms,email,' . ($admID ? $admID->id : 'null'),
                'password' => 'sometimes|min:6',
            ];
        }


        return [
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:adms,email',
            'password' => 'required|min:6',
        ];    
    }
    public function messages(): array
    {
        return [
            'nome.required' => 'Campo nome do ADM é obrigatório.',
            'email.required' => 'Campo e-mail do ADM é obrigatório.',
            'email.email' => 'E-mail do ADM inválido.',
            'email.unique' => 'E-mail do ADM já cadastrado.',
            'password.required' => 'Campo senha é obrigatório.',
            'password.min' => 'Senha deve ter no mínimo :min caracteres.',
        ];
    }
}
