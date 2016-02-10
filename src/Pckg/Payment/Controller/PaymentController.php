<?php namespace Pckg\Payment\Controller;

use Net\Http\BaseController;
use Pckg\Payment\Adapter\Product\ZoneOrder;
use Pckg\Payment\Handler\Paypal;
use Pckg\Payment\Service\Payment;
use Zone\Listing\Listing;

class PaymentController extends BaseController
{

    public function getPayment()
    {
        $payment = new Payment();
        $payment->setOrder(new ZoneOrder(Listing::first()));
        $payment->setHandler(Paypal::class);

        return view('payment.test');
    }

}