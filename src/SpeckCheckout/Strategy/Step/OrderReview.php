<?php

namespace SpeckCheckout\Strategy\Step;

class OrderReview extends AbstractOnSiteStep
{
    public function getRedirectRoute()
    {
        return 'checkout/order/review';
    }
}
