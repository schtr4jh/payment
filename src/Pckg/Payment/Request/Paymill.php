<?php namespace Pckg\Payment\Request;

use Laraplus\Http\FormRequest;
use Net\Http\Requests\Creation\FormatFormRequest;

class Paymill extends FormRequest
{

    use FormatFormRequest;

    public function rules()
    {
        return [
            'holder'     => 'required',
            'number'     => 'required',
            'exp_month'  => 'required',
            'exp_year'   => 'required',
            'cvc'        => 'required',
            'amount_int' => 'required',
        ];
    }

}