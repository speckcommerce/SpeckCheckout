<?php

namespace SpeckCheckout\PaymentMethod;

use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ConfigInterface;

class PaymentMethodPluginManager extends AbstractPluginManager
{
     protected $invokableClasses = array(
        'check' => 'SpeckCheckout\PaymentMethod\Check',
        'fax'   => 'SpeckCheckout\PaymentMethod\Fax',
        'phone' => 'SpeckCheckout\PaymentMethod\Phone',
        'po'    => 'SpeckCheckout\PaymentMethod\PO',
        'quote' => 'SpeckCheckout\PaymentMethod\Quote',
     );

    public function __construct(ConfigInterface $configuration = null)
    {
        parent::__construct($configuration);
    }

    public function validatePlugin($plugin)
    {
        if ($plugin instanceof AbstractPaymentMethod) {
            // Plugin is a valid payment method.
            return;
        }

        throw new \RuntimeException(sprintf(
            'Plugin of type %s is invalid; must Extend %s\AbstractPaymentMethod',
            (is_object($plugin) ? get_class($plugin) : gettype($plugin)),
            __NAMESPACE__
        ));
    }
}
