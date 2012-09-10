<?php

namespace SpeckCheckout\Strategy\Step;

use SpeckCheckout\Strategy\AbstractCheckoutStrategy;

abstract class AbstractStep
{
    protected $strategy;

    abstract public function isComplete();

    public function getStrategy()
    {
        return $this->strategy;
    }

    public function setStrategy(AbstractCheckoutStrategy $strategy)
    {
        $this->strategy = $strategy;
        return $this;
    }
}
