<?php namespace Pckg\Payment\Handler\Paymill;

use Illuminate\Http\Request as HttpRequest;
use Paymill\Models\Request\Checksum;
use Paymill\Models\Request\Transaction;
use Pckg\Payment\Handler\Paymill;

class Paypal extends Paymill
{

    public function validate(HttpRequest $request)
    {
        return [
            'success'  => true,
            'checksum' => $this->getChecksum(),
        ];
    }

    public function getChecksum()
    {
        $checksum = new Checksum();
        $checksum->setChecksumType(Checksum::TYPE_PAYPAL)
            ->setAmount($this->getTotalToPay())
            ->setCurrency('EUR')
            ->setDescription('Description')
            ->setReturnUrl($this->getReturnUrl())
            ->setCancelUrl($this->getCancelUrl());

        $response = $this->paymill->create($checksum);

        return $response->getId();
    }

    private function getReturnUrl()
    {
        return full_url(config('payment.paymill-paypal.url_return'), [
            'handler' => 'paymill-paypal',
            'listing' => $this->order->getOrder(),
        ]);
    }

    private function getCancelUrl()
    {
        return full_url(config('payment.paymill-paypal.url_cancel'), [
            'handler' => 'paymill-paypal',
            'listing' => $this->order->getOrder(),
        ]);
    }

    public function success()
    {
        $transaction = new Transaction();
        $transaction->setId(request('paymill_trx_id'));

        $response = $this->paymill->getOne($transaction);

        if ($response->getStatus() == 'closed') {
            $this->order->setPaid();
        }
    }

}