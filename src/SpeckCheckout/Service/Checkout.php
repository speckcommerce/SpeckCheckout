<?php

namespace SpeckCheckout\Service;

use SpeckCheckout\Strategy\Step\AbstractOffSiteStep;
use SpeckCheckout\Strategy\Step\AbstractOnSiteStep;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\Container;

class Checkout implements ServiceLocatorAwareInterface
{
    protected $options;
    protected $locator;

    public function getCheckoutStrategy()
    {
        $container = new Container('speck_checkout_strategy');

        if (isset($container->strategy)) {
            return $container->strategy;
        }

        return $this->getOptions()->getStrategy();
    }

    public function getCheckoutCurrentStep()
    {
        $steps = $this->getCheckoutStrategy()->getSteps();

        foreach ($steps as $step) {
            if ($step->isComplete()) {
                continue;
            }

            if ($step instanceof AbstractOnSiteStep) {
                return array(
                    'route' => $step->getRedirectRoute(),
                );
            }
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
