<?php

namespace SpeckCheckout\Strategy\Step;

use SpeckCheckout\Strategy\AbstractStrategy;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ConfigInterface;

class StepPluginManager extends AbstractPluginManager
{
    protected $invokableClasses = array(
        'userinfo'     => 'SpeckCheckout\Strategy\Step\UserInformation',
        'processorder' => 'SpeckCheckout\Strategy\Step\ProcessOrder',
        'paymentinfo'  => 'SpeckCheckout\Strategy\Step\PaymentInformation',
        'revieworder'  => 'SpeckCheckout\Strategy\Step\OrderReview',
    );

    public function __construct(ConfigInterface $configuration = null)
    {
        parent::__construct($configuration);

        $this->addInitializer(array($this, 'injectEventManager'));
    }

    public function injectEventManager(AbstractStep $step)
    {
        $em = $this->getServiceLocator()->get('EventManager');
        $step->setEventManager($em);
    }

    public function validatePlugin($plugin)
    {
        if ($plugin instanceof AbstractStep) {
            // we're okay
            return;
        }

        throw new \RuntimeException(sprintf(
            'Plugin of type %s is invalid; must Extend %s\AbstractStep',
            (is_object($plugin) ? get_class($plugin) : gettype($plugin)),
            __NAMESPACE__
        ));
    }
}
