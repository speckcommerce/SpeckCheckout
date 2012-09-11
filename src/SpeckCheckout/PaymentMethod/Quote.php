<?php

namespace SpeckCheckout\PaymentMethod;

class Quote extends AbstractOnSitePaymentMethod
{
    protected $paymentMethod = 'quote';
    protected $displayName = 'Quote';

    public function getActionResponse($controller)
    {
    }
}
