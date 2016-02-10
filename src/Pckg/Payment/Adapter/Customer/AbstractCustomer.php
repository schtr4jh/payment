<?php namespace Pckg\Payment\Adapter\Customer;

use Pckg\Payment\Adapter\Customer;

abstract class AbstractCustomer implements Customer
{

    protected $customer;

    public function __construct($customer)
    {
        $this->customer = $customer;
    }

}