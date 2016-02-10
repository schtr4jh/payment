<?php namespace Pckg\Payment\Service;

use Pckg\Payment\Adapter\Order;
use Pckg\Payment\Handler\Handler;
use Pckg\Payment\Service\Snippet\Handlers;

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

}