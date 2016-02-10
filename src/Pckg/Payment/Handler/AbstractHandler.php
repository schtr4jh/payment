<?php namespace Pckg\Payment\Handler;

abstract class AbstractHandler implements Handler
{

    protected $config = [];

    protected $dev = true;

    public function isDev()
    {
        return $this->dev;
    }

    public function isProd()
    {
        return !$this->dev;
    }

}