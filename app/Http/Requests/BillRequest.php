<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BillRequest extends FormRequest
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
            'message' => $validator->errors(),
        ], 422)); // status 422 indica um erro de validação
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'bill_value' => 'required|decimal:2',
            'due_date' => 'required|date'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => ' nome é obrigatório.',
            'bill_value.required' => 'valor é obrigatório.',
            'bill_value.decimal' => 'valor é deve ser decimal.',
            'due_date.required' => ' vencimento é obrigatório.',
            'due_date.date' => ' vencimento deve ser no formato AAAA-MM-DD.',
        ];
    }


}
