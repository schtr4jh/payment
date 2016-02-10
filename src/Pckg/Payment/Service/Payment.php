<?php namespace Pckg\Payment\Service;

use Pckg\Payment\Adapter\Order;
use Pckg\Payment\Handler\Handler;
use Pckg\Payment\Handler\Paymill;
use Pckg\Payment\Handler\Paypal;
use Pckg\Payment\Handler\PaypalRest;
use Pckg\Payment\Handler\Proforma;

class Payment
{

    protected $order;

    protected $handler;

    public function setOrder(Order $order)
    {
        $this->order = $order;

        return $this;
    }

    public function setHandlerClass($handler)
    {
        $handler = new $handler($this->order);
        $handler->initHandler();

        return $this->setHandler($handler);
    }

    public function setHandler(Handler $handler)
    {
        $this->handler = $handler;

        return $this;
    }

    public function getHandler()
    {
        return $this->handler;
    }

    public function getTotal()
    {
        return $this->handler->getTotal();
    }

    public function getCurrency()
    {
        return $this->order->getCurrency();
    }

    public function usePaymillHandler()
    {
        $this->handler = new Paymill($this->order);
        $this->handler->initHandler();
    }

    public function usePaypalHandler()
    {
        $this->handler = new Paypal($this->order);
        $this->handler->initHandler();
    }

    public function usePaypalRestHandler()
    {
        $this->handler = new PaypalRest($this->order);
        $this->handler->initHandler();
    }

    public function useProformaHandler()
    {
        $this->handler = new Proforma($this->order);
        $this->handler->initHandler();
    }

}