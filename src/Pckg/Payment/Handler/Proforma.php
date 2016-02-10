<?php namespace Pckg\Payment\Handler;

class Proforma extends AbstractHandler implements Handler
{

    public function initHandler()
    {
        $this->config = [
            'url_waiting' => config('payment.proforma.url_waiting'),
        ];
    }

    public function start()
    {
        redirect()->away(url($this->config['url_waiting'], ['proforma', $this->order->getOrder()]))->send();
    }

    public function waiting()
    {

    }

}