<?php

namespace SpeckCheckout\Strategy\Step;

class ProcessOrder extends AbstractOnSiteStep
{
    public function getRedirectRoute()
    {
        return 'order/process';
    }
}
