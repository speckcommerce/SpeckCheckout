<?php

namespace SpeckCheckout\Strategy\Step;

use SpeckCheckout\Strategy\AbstractCheckoutStrategy;

abstract class AbstractStep
{
    protected $strategy;

    protected $complete = false;

    protected $eventManager;

    public function setComplete($complete)
    {
        // these 2 lines are REALLY hacky, figure out whats going on with multisite + this module
        $this->__sleep();
        $this->getStrategy()->__destruct();

        $this->getEventManager()->trigger(__FUNCTION__, $this, array('complete' => $complete));
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

    /**
     * @return eventManager
     */
    public function getEventManager()
    {
        if (null === $this->getEventManager()) {
            $this->eventManager = $this->getStrategy()->getServiceLocator()->get('EventManager');
        }

        return $this->eventManager;
    }

    /**
     * @param $eventManager
     * @return self
     */
    public function setEventManager($eventManager)
    {
        $this->eventManager = $eventManager;
        return $this;
    }
}
