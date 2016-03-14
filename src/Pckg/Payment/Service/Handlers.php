<?php namespace Pckg\Payment\Service;

use Pckg\Payment\Handler\Paymill;
use Pckg\Payment\Handler\Paypal;
use Pckg\Payment\Handler\PaypalRest;
use Pckg\Payment\Handler\Proforma;

trait Handlers
{

    public function usePaymillHandler()
    {
        $this->handler = new Paymill($this->order);
        $this->handler->initHandler();

        return $this;
    }

    public function usePaymillSepaHandler()
    {
        $this->handler = new Paymill\Sepa($this->order);
        $this->handler->initHandler();

        return $this;
    }

    public function usePaymillPaypalHandler()
    {
        $this->handler = new Paymill\Paypal($this->order);
        $this->handler->initHandler();

        return $this;
    }

    public function usePaypalHandler()
    {
        $this->handler = new Paypal($this->order);
        $this->handler->initHandler();

        return $this;
    }

    public function usePaypalRestHandler()
    {
        $this->handler = new PaypalRest($this->order);
        $this->handler->initHandler();

        return $this;
    }

    public function useProformaHandler()
    {
        $this->handler = new Proforma($this->order);
        $this->handler->initHandler();

        return $this;
    }

}