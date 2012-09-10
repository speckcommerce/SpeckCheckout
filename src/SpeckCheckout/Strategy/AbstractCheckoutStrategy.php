<?php

namespace SpeckCheckout\Strategy;

use SpeckAddress\Entity\Address;
use SpeckCheckout\Entity\Order;
use Zend\Stdlib\SplQueue;

abstract class AbstractCheckoutStrategy
{
    /**
     * @var Serializable
     */
    protected $storage = null;

    /**
     * @var SplQueue[Step]
     */
    protected $steps = null;

    /**
     * @return Address|null
     */
    abstract public function getShippingAddress();

    /**
     * @return Address|null
     */
    abstract public function getBillingAddress();

    /**
     * @return string|null
     */
    abstract public function getEmailAddress();

    /**
     * @return string
     */
    abstract public function getPaymentMethod();

    /**
     * @return Order|null
     */
    abstract public function getOrder();

    /**
     * @return boolean
     */
    abstract public function isStarted();

    /**
     * @return boolean
     */
    abstract public function isComplete();

    /**
     * @return SplQueue
     */
    public function getSteps()
    {
        return $this->steps;
    }

    /**
     * @return Step\AbstractStep
     */
    public function getFirstStep()
    {
        return $this->steps->top();
    }

    /**
     * @return Step\AbstractStep
     */
    public function getNextStep()
    {
        foreach ($this->getSteps() as $step) {
            if ($step->isComplete()) {
                continue;
            } else {
                return $step;
            }
        }
    }
}
