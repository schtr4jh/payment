<?php namespace Pckg\Payment\Handler;

class Paypal extends AbstractHandler implements Handler
{

    public function fetchConfig()
    {
        $this->config = [
            'username'  => config('payment.paypall.username'),
            'password'  => config('payment.paypall.password'),
            'signature' => config('payment.paypall.signature'),
            'url'       => config('payment.paypall.url'),
            'url_token' => config('payment.paypall.url_token'),
        ];

        return $this;
    }
}