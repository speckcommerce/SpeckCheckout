<?php

namespace SpeckCheckout\Strategy\Step;

class UserInformation extends AbstractOnSiteStep
{
    public function getRedirectRoute()
    {
        return 'checkout/user-information/step-one';
    }

    public function isComplete()
    {
    }
}
