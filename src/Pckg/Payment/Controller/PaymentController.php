<?php namespace Pckg\Payment\Controller;

use Net\Http\BaseController;
use Pckg\Payment\Adapter\Product\ZoneOrder;
use Pckg\Payment\Handler\Paymill;
use Pckg\Payment\Service\Payment;
use Zone\Listing\Listing;

class PaymentController extends BaseController
{

    protected $paymentService;

    public function __construct()
    {
        $this->vendorAsset('schtr4jh/payment/src/Pckg/Payment/Resources/scripts/payment.js');
        $this->paymentService = new Payment();
    }

    public function getPayment()
    {
        $this->paymentService->setOrder(new ZoneOrder(Listing::first()));
        $this->paymentService->setHandlerClass(Paymill::class);

        return view('payment.test', [
            'paymentService' => $this->paymentService,
            'paymill'        => $this->paymentService->getHandler(),
        ]);
    }

    public function postStart($handler)
    {
        $this->paymentService->setOrder(new ZoneOrder(Listing::first()));

        $this->paymentService->{'use' . ucfirst($handler)}();

        $this->paymentService->getHandler()->postStart();
    }

}