<?php namespace Pckg\Payment\Request\Paymill;

use Laraplus\Http\FormRequest;
use Net\Http\Requests\Creation\FormatFormRequest;

class Sepa extends FormRequest
{

    use FormatFormRequest;

    public function rules()
    {
        return [
            'accountholder' => 'required',
            'iban'          => 'required',
            'bic'           => 'required',
        ];
    }

}