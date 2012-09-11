<?php

namespace SpeckCheckout\PaymentMethod;

abstract class AbstractPaymentMethod
{
    protected $paymentMethod;
    protected $displayName;
    protected $data;
    protected $viewPartialName;

    abstract public function getActionResponse($controller);

    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
        return $this;
    }

    public function getDisplayName()
    {
        return $this->displayName;
    }

    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function getViewPartialName()
    {
        return $this->viewPartialName;
    }

    public function setViewPartialName($viewPartialName)
    {
        $this->viewPartialName = $viewPartialName;
        return $this;
    }
}
