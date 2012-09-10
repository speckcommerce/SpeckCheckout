<?php

namespace SpeckCheckout\Options;

use Zend\Stdlib\AbstractOptions;

class CheckoutOptions extends AbstractOptions
{
    /**
     * Turn off strict options mode
     */
    protected $__strictMode__ = false;

    protected $strategy;

    public function getStrategy()
    {
        return $this->strategy;
    }

    public function setStrategy($strategy)
    {
        $this->strategy = $strategy;
        return $this;
    }
}
