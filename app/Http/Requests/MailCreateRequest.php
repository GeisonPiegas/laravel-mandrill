<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class MailCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nome' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'assunto' => 'required|string|max:100',
            'corpo_email' => 'required|string',
            'agendar' => 'date|nullable',
        ];
    }

    public function messages()
    {
        return [
            'nome.required' => 'Nome é obrigatório!',
            'nome.string' => 'Nome deve ser texto!',
            'nome.max' => 'Nome deve ter no maximo :max carateres!',

            'email.required' => 'E-mail é obrigatório!',
            'email.string' => 'E-mail deve ser texto!',
            'email.max' => 'Nome deve ter no maximo :max carateres!',

            'assunto.required' => 'Assunto é obrigatório!',
            'assunto.string' => 'Assunto deve ser texto!',
            'assunto.max' => 'Nome deve ter no maximo :max carateres!',

            'corpo_email.required' => 'Corpo do e-mail é obrigatório!',
            'corpo_email.string' => 'Corpo do e-mail deve ser texto!',

            'agendar.date' => 'Agendamento deve ter uma data valida!',
        ];
    }
    
     /**
    * [failedValidation [Overriding the event validator for custom error response]]
    * @param  Validator $validator [description]
    * @return [object][object of various validation errors]
    */
    public function failedValidation(Validator $validator) { 
       throw new HttpResponseException(response()->json($validator->errors(), 422)); 
   }
}
