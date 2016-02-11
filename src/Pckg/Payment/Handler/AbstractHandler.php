<?php namespace Pckg\Payment\Handler;

use Pckg\Payment\Adapter\Order;

abstract class AbstractHandler implements Handler
{

    protected $config = [];

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function initHandler()
    {
        return $this;
    }

    public function log($data)
    {

    }

}