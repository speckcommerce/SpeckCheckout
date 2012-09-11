<?php

namespace SpeckCheckout\Strategy\Step;

class PaymentInformation extends AbstractOnSiteStep
{
    public function getRedirectRoute()
    {
        return 'checkout/payment/choose';
    }
}
