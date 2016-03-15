# About project
Project provides abstract implementation for payment providers:
 - paypal (classic API, REST API)
 - paymill (credit cards, sepa payment, paypal)
 - proforma
 
# Instalation
You can install package via composer.
```sh
$ composer require schtr4jh/payment
```
## Paypal classic API
### Config
```php
        'enabled'    => true,
        'username'   => 'schtr4jh-facilitator_api1.schtr4jh.net',
        'password'   => '1390585136',
        'signature'  => 'AOZR6pqlRlwo0Ex9.oQbP2uvOalsAHQdlhdfczB0.B699lqJXv8pigFj',
        'url'        => 'https://api-3t.sandbox.paypal.com/nvp',
        'url_token'  => 'https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=[token]',
        'url_return' => 'payment.success',
        'url_cancel' => 'payment.error',
```
## Paypal REST API
### Config
```php
        'enabled'    => false,
        'id'         => 'AYlzupNqmg7177ZI57MfI26mgkzN-n8QQewuicaHmi7OOEf5LNy5FIi7ooFIbwo-t9_LQR9NOqtLslF_',
        'secret'     => 'ECQLZ6zyH8b6JgG3hGN8Qg1u6ezDiT0HpfCK7MnKcQyyQoYtghnAndYJLu5p1g0UJmLMj7_IV1Qdbsv3',
        'mode'       => 'sandbox',
        'url_return' => 'payment.success',
        'url_cancel' => 'payment.error',
        'log'        => [
            'enabled' => true,
            'level'   => 'DEBUG', // 'FINE' for production
        ]
```
## Paymill
### Config
```php
        'enabled'     => true,
        'private_key' => '6813f7bc057744e5b56bbbb5493cbc86',
        'public_key'  => '50014699537b68adbd545e784ebf514d',
```
## Proforma
### Config
```php
        'enabled'     => false,
        'url_waiting' => 'payment.waiting',
```
# Implementation
Each project needs to have implemented \Pckg\Payment\Adapter\Order|Product|Customer|Log (called ProjectOrder, ProjectProduct, ProjectCustomer and ProductLog) which provides proper mappers. Order object is stored in $order.
Then we need to create payment service and assign order.
```php
$service = new \Pckg\Payment\Service\Payment();
$service->setOrder(new ProjectOrder($order));
```
## Routes
```php
// show payment options
'payment'         => route('PaymentController@payment', 'payment/{order}', 'GET'),

// validate paymill request
'payment.paymill' => route('PaymentController@paymill', 'payment/paymill/validate', 'POST'),

// start handler (post for paymill, get for paypal) - calls start() on handler
'payment.start'   => route('PaymentController@start', 'payment/{handler}/{order}/start', 'POST|GET'),

// success handler - calls success() on handler
'payment.success' => route('PaymentController@success', 'payment/{handler}/{order}/success', 'GET'),

// error handler - calls error() on handler
'payment.error'   => route('PaymentController@error', 'payment/{handler}/{order}/error', 'GET'),

// waiting for payment page (proforma)
'payment.waiting' => route('PaymentController@waiting', 'payment/{handler}/{order}/waiting', 'GET'),
```
## Controller
```php
<?php 

use Pckg\Payment\Request\Paymill;
use Pckg\Payment\Service\Payment;
use Project\Order\Order;
use Project\Order\PaymentMethod;

class PaymentController
{

    protected $paymentService;

    public function __construct()
    {
        $this->paymentService = new Payment();
    }

    protected function preparePaymentService($handler, Order $order)
    {
        $this->paymentService->setOrder(new ProjectOrder($order));
        $this->paymentService->{'use' . ucfirst($handler) . 'Handler'}();
        $this->paymentService->getHandler()->setLogger(new ProjectLog($order));
    }

    public function getPayment(Order $order)
    {
        $this->paymentService->setOrder(new ProjectOrder($order));

        return view('payment.index', [
            'order'          => $order,
            'paymentMethods' => PaymentMethod::where('enabled', 1)->get(),
            'paymentService' => $this->paymentService,
        ]);
    }

    public function postStart($handler, Order $order)
    {
        $this->preparePaymentService($handler, $order);
        $success = $this->paymentService->getHandler()->start();

        return [
            'success' => true,
            'modal'   => $success
                ? '#dialog-payment-success'
                : '#dialog-payment-error',
        ];
    }

    public function getStart($handler, Order $order)
    {
        return $this->getStatus($handler, $order, 'start');
    }

    public function getSuccess($handler, Order $order)
    {
        return $this->getStatus($handler, $order, 'success');
    }

    public function getError($handler, Order $order)
    {
        return $this->getStatus($handler, $order, 'error');
    }

    public function getWaiting($handler, Order $order)
    {
        return $this->getStatus($handler, $order, 'waiting');
    }

    protected function getStatus($handler, Order $order, $action)
    {
        $this->preparePaymentService($handler, $order);
        $this->paymentService->getHandler()->{$action}();

        return view('payment.' . $action, [
            'order' => $order,
        ]);
    }

    public function postPaymill(Paymill $request)
    {
        // form request will take care of it =)
        return [
            'success' => true,
        ];
    }

}
```
## Handlers
### Paymill
```php
$service->usePaymillHandler();
$service->getHandler()->setLogger(new ProjectLog($order));
$service->getHandler()->start(); // reads $_POST['token'] and executes transacion
```
### Paypal
```php
$service->usePaypalHandler();
$service->getHandler()->setLogger(new ProjectLog($order));
$service->getHandler()->start(); // redirects user to paypal
```
