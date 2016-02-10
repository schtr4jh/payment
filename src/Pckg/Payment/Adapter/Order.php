<?php namespace Pckg\Payment\Adapter;

interface Order
{

    public function getId();

    public function getTotal();

    public function getVat();

    public function getDelivery();

    public function getDate();

}