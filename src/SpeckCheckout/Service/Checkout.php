<?php

namespace SpeckCheckout\Service;

use SpeckCheckout\Strategy\Step\AbstractOffSiteStep;
use SpeckCheckout\Strategy\Step\AbstractOnSiteStep;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Checkout implements ServiceLocatorAwareInterface
{
    protected $options;
    protected $locator;

    public function getCheckoutEntryPoint()
    {
        $entryPoint = $this->getOptions()->getStrategy()->getFirstStep();

        if ($entryPoint instanceof AbstractOnSiteStep) {
            return array(
                'route' => $entryPoint->getRedirectRoute(),
            );
        }
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    public function getServiceLocator()
    {
        return $this->locator;
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->locator = $serviceLocator;
        return $this;
    }
}
