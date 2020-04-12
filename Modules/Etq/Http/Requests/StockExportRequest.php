<?php

namespace Modules\Etq\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockExportRequest extends FormRequest
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
        $rules['data_ref'] = 'required|date';
        if (!$this->request->get('firmas')) {
            $rules['empresa.0'] = 'required';
        }
        if (!$this->request->get('filiais')) {
            $rules['filiais.0'] = 'required';
        }

        if (!$this->request->get('tipos')) {
            $rules['categoria.0'] = 'required';
        }

        return $rules;
    }

    /** Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        $messages = [];
        $messages['data_ref.required'] = 'Digite uma data valída.';
        $messages['empresa.0.required'] = 'Selecione uma Empresa.';
        $messages['filiais.0.required'] = 'Selecione uma Filial.';
        $messages['categoria.0.required'] = 'Selecione uma Categoria.';

        return $messages;

    }

}
