<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;


class ResetPasswordValidateCodeRequest extends FormRequest
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
        ], 422));  // O código de status HTTP 422 significa "Unprocessable Entity" (Entidade Não Processável). Esse código é usado quando o servidor entende a requisição do cliente, mas não pode processá-la devido a erros de validação no lado do servidor.
    }




    public function rules(): array
    {

        return [
            'code' => 'required',
            'email' => 'required|email',
        ];
    }


    public function messages(): array
    {
        return [
            'code.required' => 'Código é obrigatório.',
            'email.required' => 'Email é obrigatório.',
            'email.email' => 'Email inválido.',
        ];
    }


}
