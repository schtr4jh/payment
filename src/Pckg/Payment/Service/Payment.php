<?php namespace Pckg\Payment\Service;

use Pckg\Payment\Adapter\Order;
use Pckg\Payment\Handler\Handler;
use Pckg\Payment\Handler\Paymill;

class Payment
{

    use Handlers;

    protected $order;

    protected $handler;

    public function setOrder(Order $order)
    {
        $this->order = $order;

        return $this;
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

    public function getTotalWithCurrency()
    {
        return number_format($this->getTotal(), 2) . ' ' . $this->getCurrency();
    }

    public function getTotalToPayWithCurrency()
    {
        return number_format($this->getTotalToPay(), 2) . ' ' . $this->getCurrency();
    }

    public function getTotal()
    {
        return $this->order->getTotal();
    }

    public function getTotalToPay()
    {
        return $this->order->getTotalToPay();
    }

    public function getCurrency()
    {
        return $this->order->getCurrency();
    }

    public function getUrl($action, $handler)
    {
        return url('payment.' . $action, [$handler, $this->order->getOrder()]);
    }

    public function has($handler)
    {
        return config('payment.' . $handler . '.enabled');
    }

}