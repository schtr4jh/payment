<?php namespace Pckg\Payment\Service\Snippet;

use Pckg\Payment\Handler\Paymill;
use Pckg\Payment\Handler\Paypal;

trait Handlers
{

    public function usePaymill()
    {
        $this->handler = new Paymill($this->order);
        $this->handler->initHandler();
    }

    public function usePaypall()
    {
        $this->handler = new Paypal($this->order);
        $this->handler->initHandler();
    }

}