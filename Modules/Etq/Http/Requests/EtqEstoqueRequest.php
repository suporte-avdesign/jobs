<?php

namespace Modules\Etq\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EtqEstoqueRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules['data_ref'] = "required";
        $firmas = $this->request->get('firmas');
        /****************************  FIRMAS *********************************/
        foreach ($firmas as $firma) {
            $rules['firmas'] = "required";
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [];
        $messages['data_ref.required'] = "A data é obrigatória";
        $firmas = $this->request->get('firmas');
        foreach ($firmas as $firma) {
            $messages['firmas'] = "Secione a Empresa";
        }

        return $messages;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
