<?php

namespace SpeckCheckout\Strategy\Step;

abstract class AbstractOnSiteStep extends AbstractStep
{
    /**
     * @return string
     */
    abstract public function getRedirectRoute();
}
