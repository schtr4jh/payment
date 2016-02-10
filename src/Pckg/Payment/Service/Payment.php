<?php namespace Pckg\Payment\Service;

use Pckg\Payment\Adapter\Order;
use Pckg\Payment\Handler\Handler;

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
        $handler = new $handler;
        $handler->fetchConfig();

        return $this->setHandler($handler);
    }

    public function setHandler(Handler $handler)
    {
        $this->handler = $handler;

        return $this;
    }

}