<?php

namespace SpeckCheckout\PaymentMethod;

class Check extends AbstractOnSitePaymentMethod
{
    protected $paymentMethod = 'check';

    protected $displayName = 'Check / Money Order';

    public function getActionResponse($controller)
    {
    }
}
