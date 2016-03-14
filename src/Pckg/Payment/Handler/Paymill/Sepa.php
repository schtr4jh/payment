<?php namespace Pckg\Payment\Handler\Paymill;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Validator;
use Pckg\Payment\Handler\Paymill;

class Sepa extends Paymill
{

    public function validate(HttpRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'accountholder' => 'required',
            'iban'          => 'required',
            'bic'           => 'required',
        ]);

        if (!$validator->passes()) {
            return new JsonResponse($validator->getMessageBag()->toArray(), 422);
        }

        return [
            'success' => true,
        ];
    }

}