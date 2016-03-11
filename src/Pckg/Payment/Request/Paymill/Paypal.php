<?php namespace Pckg\Payment\Request\Paymill;

use Laraplus\Http\FormRequest;
use Net\Http\Requests\Creation\FormatFormRequest;

class Paypal extends FormRequest
{

    use FormatFormRequest;

    public function rules()
    {
        return [];
    }

}