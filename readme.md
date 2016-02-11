<?php

use Pckg\Payment\Service\Payment;
use Project\Payment\ProjectOrder; // implements Pckg\Payment\Adapter\Order;
use Project\Payment\ProjectLog; // implements Pckg\Payment\Adapter\Log;

$order = receive_order_from_DB();

// create payment service
$service = new Payment();

// set order
$service->setOrder(new ProjectOrder($order));

// paymill example
$service->usePaymillHandler(); // usePaypallHandler(), useProformaHandler(), usePaypalRestHandler()
$service->getHandler()->setLogger(new ProjectLog($order));
$service->getHandler()->start(); // reads $_POST['token'] and executes transacion

// paypal example
$service->usePaypalHandler();
$service->getHandler()->setLogger(new ProjectLog($order));
$service->getHandler()->start(); // redirects user to paypal

// config example is available at Zone-Project (resources\config\payment.php)

// route examples
// show payment options
'payment'         => route('PaymentController@payment', 'payment/{listing}', 'GET'),

// validate paymill request
'payment.paymill' => route('PaymentController@paymill', 'payment/paymill/validate', 'POST'),

// start handler (post for paymill, get for paypal) - calls start() on handler
'payment.start'   => route('PaymentController@start', 'payment/{handler}/{listing}/start', 'POST|GET'),

// success handler - calls success() on handler
'payment.success' => route('PaymentController@success', 'payment/{handler}/{listing}/success', 'GET'),

// error handler - calls error() on handler
'payment.error'   => route('PaymentController@error', 'payment/{handler}/{listing}/error', 'GET'),

// waiting for payment page (proforma)
'payment.waiting' => route('PaymentController@waiting', 'payment/{handler}/{listing}/waiting', 'GET'),