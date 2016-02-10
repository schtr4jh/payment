<?php namespace Pckg\Payment\Handler;

class Paymill extends AbstractHandler implements Handler
{

    public function fetchConfig()
    {
        $this->config = [
            'private_key' => config('payment.paymill.private_key'),
            'public_key'  => config('payment.paymill.public_key'),
        ];

        return $this;
    }

}