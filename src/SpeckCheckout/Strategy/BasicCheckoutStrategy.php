<?php

namespace SpeckCheckout\Strategy;

use SpeckAddress\Entity\Address;
use SpeckCheckout\Entity\Order;
use Zend\Stdlib\SplQueue;

class BasicCheckoutStrategy extends AbstractCheckoutStrategy
{
    public function __construct(array $steps)
    {
        $this->steps = new SplQueue;

        foreach ($steps as $i) {
            $i->setStrategy($this);
            $this->steps->enqueue($i);
        }
    }
}
