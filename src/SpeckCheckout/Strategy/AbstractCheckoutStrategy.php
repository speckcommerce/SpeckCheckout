<?php

namespace SpeckCheckout\Strategy;

use SpeckAddress\Entity\Address;
use SpeckCheckout\Entity\Order;
use Zend\Session\Container;
use Zend\Stdlib\SplQueue;

abstract class AbstractCheckoutStrategy
{
    /**
     * @var SplQueue[Step]
     */
    protected $steps = null;

    /**
     * @var Address
     */
    protected $shippingAddress;

    /**
     * @var Address
     */
    protected $billingAddress;

    /**
     * @var string
     */
    protected $emailAddress;

    /**
     * @var string
     */
    protected $paymentMethod;

    /**
     * @var Order
     */
    protected $order;

    /**
     * @var boolean
     */
    protected $started = false;

    /**
     * @var boolean
     */
    protected $complete = false;

    public function __destruct()
    {
        $container = new Container('speck_checkout_strategy');
        $container->strategy = $this;
    }

    public function __wakeup()
    {
        foreach($this->steps as $step) {
            $step->setStrategy($this);
        }
    }


    /**
     * @return SplStack
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

    public function getShippingAddress()
    {
        return $this->shippingAddress;
    }

    public function setShippingAddress($shippingAddress)
    {
        $this->shippingAddress = $shippingAddress;
        return $this;
    }

    public function getBillingAddress()
    {
        return $this->billingAddress;
    }

    public function setBillingAddress($billingAddress)
    {
        $this->billingAddress = $billingAddress;
        return $this;
    }

    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;
        return $this;
    }

    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
        return $this;
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function setOrder($order)
    {
        $this->order = $order;
        return $this;
    }

    public function getStarted()
    {
        return $this->started;
    }

    public function setStarted($started)
    {
        $this->started = $started;
        return $this;
    }

    public function getComplete()
    {
        return $this->complete;
    }

    public function setComplete($complete)
    {
        $this->complete = $complete;
        return $this;
    }
}
