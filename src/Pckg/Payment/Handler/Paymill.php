<?php namespace Pckg\Payment\Handler;

use Paymill\Models\Request\Payment;
use Paymill\Models\Request\Transaction;
use Paymill\Request;

class Paymill extends AbstractHandler implements Handler
{

    protected $paymill;

    public function initHandler()
    {
        $this->config = [
            'private_key' => config('payment.paymill.private_key'),
            'public_key'  => config('payment.paymill.public_key'),
        ];

        $this->paymill = new Request($this->config['private_key']);

        return $this;
    }

    public function getTotal()
    {
        return round($this->order->getTotal() * 100);
    }

    public function getPublicKey()
    {
        return $this->config['public_key'];
    }

    public function start()
    {
        $payment = new Payment();
        $payment->setToken(request('token'));
        $payment->setClient($this->order->getCustomer());

        $response = null;
        try {
            $response = $this->paymill->create($payment);

        } catch (\Exception $e) {
            throw $e;

        } finally {
            if ($paymentId = $response->getId()) {
                $this->makeTransaction($paymentId);
            }
        }
    }

    protected function makeTransaction($paymentId)
    {
        $transaction = new Transaction();
        $transaction->setAmount($this->getTotal())
            ->setCurrency($this->order->getCurrency())
            ->setPayment($paymentId)
            ->setDescription($this->order->getDescription());

        $response = null;
        try {
            $response = $this->paymill->create($transaction);

        } catch (\Exception $e) {
            throw $e;

        } finally {
            if ($response->getStatus() == 'closed') {
                $this->order->setPaid();
            }

        }
    }

    protected function handleTransactionResponse($response)
    {
        if ($response->getStatus() == 'closed') {
            $this->order->setPaid();
        }
    }

}