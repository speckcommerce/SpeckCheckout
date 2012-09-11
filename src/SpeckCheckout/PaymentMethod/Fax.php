<?php

namespace SpeckCheckout\PaymentMethod;

class Fax extends AbstractOnSitePaymentMethod
{
    protected $paymentMethod = 'fax';

    protected $displayName = 'Fax';

    public function getActionResponse($controller)
    {
    }
}
