<?php namespace Pckg\Payment\Handler;

interface Handler
{

    public function fetchConfig();

    public function isDev();

    public function isProd();

}