<?php

namespace SpeckCheckout\Strategy;

use Zend\Stdlib\ParametersInterface;
use Zend\Uri\UriInterface;

abstract class AbstractOffSiteStep extends AbstractStep
{
    /**
     * @return UriInterface
     */
    abstract public function getRedirectUrl();

    /**
     * @return ParametersInterface
     */
    abstract public function getRedirectParameters();

    /**
     * @param ParmetersInterface $responseParams
     * @return AbstractOffSiteCheckoutStrategy
     */
    abstract public function setResponse(ParametersInterface $responseParams);
}
