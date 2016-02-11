# About project
Project provides abstract implementation for payment providers:
 - paypal (classic API + REST API)
 - paymill
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
```
## Controller
```php
<?php namespace Net\Http;

use Net\Http\Payment\ZoneLog;
use Net\Http\Payment\ZoneOrder;
use Pckg\Payment\Request\Paymill;
use Pckg\Payment\Service\Payment;
use Zone\Listing\Listing;
use Zone\Listing\PaymentMethod;

class PaymentController extends BaseController
{

    protected $paymentService;

    public function __construct()
    {
        $this->paymentService = new Payment();
    }

    protected function preparePaymentService($handler, Listing $listing)
    {
        $listing->abortIfNotMine();

        $this->paymentService->setOrder(new ZoneOrder($listing));
        $this->paymentService->{'use' . ucfirst($handler) . 'Handler'}();
        $this->paymentService->getHandler()->setLogger(new ZoneLog($listing));
    }

    public function getPayment(Listing $listing)
    {
        $listing->abortIfNotMine();
        $this->paymentService->setOrder(new ZoneOrder($listing));

        return view('payment.index', [
            'listing'        => $listing,
            'paymentMethods' => PaymentMethod::where('enabled', 1)->get(),
            'paymentService' => $this->paymentService,
        ]);
    }

    public function postStart($handler, Listing $listing)
    {
        $this->preparePaymentService($handler, $listing);
        $success = $this->paymentService->getHandler()->start();

        return [
            'success' => true,
            'modal'   => $success
                ? '#dialog-payment-success'
                : '#dialog-payment-error',
        ];
    }

    public function getStart($handler, Listing $listing)
    {
        return $this->getStatus($handler, $listing, 'start');
    }

    public function getSuccess($handler, Listing $listing)
    {
        return $this->getStatus($handler, $listing, 'success');
    }

    public function getError($handler, Listing $listing)
    {
        return $this->getStatus($handler, $listing, 'error');
    }

    public function getWaiting($handler, Listing $listing)
    {
        return $this->getStatus($handler, $listing, 'waiting');
    }

    protected function getStatus($handler, Listing $listing, $action)
    {
        $this->preparePaymentService($handler, $listing);
        $this->paymentService->getHandler()->{$action}();

        return view('payment.' . $action, [
            'listing' => $listing,
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