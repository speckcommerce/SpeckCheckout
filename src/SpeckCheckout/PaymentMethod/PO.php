<?php

namespace SpeckCheckout\PaymentMethod;

class PO extends AbstractOnSitePaymentMethod
{
    protected $paymentMethod = 'po';
    protected $displayName = 'Purchase Order';

    public function getActionResponse($controller)
    {
    }
}
