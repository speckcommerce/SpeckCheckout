<?php

namespace SpeckCheckout\Strategy;

use SpeckAddress\Entity\Address;
use SpeckCheckout\Entity\Order;
use Zend\Stdlib\SplQueue;

class BasicCheckoutStrategy extends AbstractCheckoutStrategy
{
    protected $shippingAddress;

    protected $billingAddress;

    protected $emailAddress;

    protected $paymentMethod;

    protected $order;

    protected $started = false;

    protected $complete = false;

    public function __construct(array $steps)
    {
        $this->steps = new SplQueue;

        foreach ($steps as $i) {
            $i->setStrategy($this);
            $this->steps->enqueue($i);
        }
    }

    public function getShippingAddress()
    {
        return $this->shippingAddress;
    }

    public function setShippingAddress($shippingAddress)
    {
        $this->shippingAddress = $shippingAddress;
        return $this;
    }

    public function getBillingAddress()
    {
        return $this->billingAddress;
    }

    public function setBillingAddress($billingAddress)
    {
        $this->billingAddress = $billingAddress;
        return $this;
    }

    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;
        return $this;
    }

    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
        return $this;
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function setOrder($order)
    {
        $this->order = $order;
        return $this;
    }

    public function isStarted()
    {
        return $this->started;
    }

    public function setStarted($started)
    {
        $this->started = $started;
        return $this;
    }

    public function isComplete()
    {
        return $this->complete;
    }

    public function setComplete($complete)
    {
        $this->complete = $complete;
        return $this;
    }
}

