<?php

namespace SpeckCheckout\Strategy\Step;

use SpeckCheckout\Strategy\AbstractCheckoutStrategy;

abstract class AbstractStep
{
    protected $strategy;

    protected $complete = false;

    public function setComplete($complete)
    {
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
