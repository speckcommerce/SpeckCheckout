<?php

namespace SpeckCheckout\Options;

use Zend\Stdlib\AbstractOptions;
use Zend\ServiceManager\Config;
use SpeckCheckout\PaymentMethod\PaymentMethodPluginManager;

class CheckoutOptions extends AbstractOptions
{
    /**
     * Turn off strict options mode
     */
    protected $__strictMode__ = false;

    protected $strategy;

    protected $paymentMethods;

    public function getStrategy()
    {
        return $this->strategy;
    }

    public function setStrategy($strategy)
    {
        $this->strategy = $strategy;
        return $this;
    }

    public function getPaymentMethods()
    {
        return $this->paymentMethods;
    }

    public function setPaymentMethods(array $paymentMethods)
    {
        $this->paymentMethods = $paymentMethods;
        return $this;
    }
}
