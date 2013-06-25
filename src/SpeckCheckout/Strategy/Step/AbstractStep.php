<?php

namespace SpeckCheckout\Strategy\Step;

use SpeckCheckout\Strategy\AbstractCheckoutStrategy;
use Zend\EventManager\EventManagerAwareTrait;
use Zend\EventManager\EventManagerAwareInterface;

abstract class AbstractStep implements EventManagerAwareInterface
{
    use EventManagerAwareTrait;

    protected $strategy;

    protected $complete = false;

    protected $eventManager;

    public function setComplete($complete)
    {
        $this->getEventManager()->trigger(__FUNCTION__, $this, array('complete' => $complete));

        // these 2 lines are REALLY hacky, figure out whats going on with multisite + this module
        $this->__sleep();
        $this->getStrategy()->__destruct();

        $this->complete = $complete;

        return $this;
    }

    public function isComplete()
    {
        return $this->complete;
    }

    public function getStrategy()
    {
        return $this->strategy;
    }

    public function setStrategy(AbstractCheckoutStrategy $strategy)
    {
        $this->strategy = $strategy;
        return $this;
    }

    public function __sleep()
    {
        return array('complete');
    }
}
