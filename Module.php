<?php

namespace SpeckCheckout;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Session\Container;

class Module implements AutoloaderProviderInterface
{
    public function getServiceConfig()
    {
        return array(
            'invokables' => array(
                'speckcheckout_step_pluginmanager' => 'SpeckCheckout\Strategy\Step\StepPluginManager',
                'speckcheckout_paymentmethod_pluginmanager' => 'SpeckCheckout\PaymentMethod\PaymentMethodPluginManager',
            ),
            'factories' => array(
                'speck_checkout_strategy'          => function($sm) {
                    $config = $sm->get('Config');

                    $steps = array();
                    $stepPluginManager = $sm->get('speckcheckout_step_pluginmanager');

                    foreach ($config['speck-checkout']['steps'] as $step) {
                        $steps[] = $stepPluginManager->get($step);
                    }

                    return new $config['speck-checkout']['strategy']($steps);
                },

                'SpeckCheckout\Service\Checkout' => function($sm) {
                    $service = new Service\Checkout;
                    $service->setOptions($sm->get('SpeckCheckout\Options\CheckoutOptions'));
                    return $service;
                },

                'SpeckCheckout\Options\CheckoutOptions' => function($sm) {
                    $config = $sm->get('application')->getConfig();
                    $options = new Options\CheckoutOptions();
                    $options->setStrategy($sm->get('speck_checkout_strategy'));

                    $paymentMethods = array();
                    $paymentMethodPluginManager = $sm->get('speckcheckout_paymentmethod_pluginmanager');
                    foreach ($config['speck-checkout']['payment_methods'] as $index => $paymentMethod) {
                        $paymentMethods[$index] = $paymentMethodPluginManager->get($paymentMethod);
                    }
                    $options->setPaymentMethods($paymentMethods);

                    return $options;
                },
            ),
        );
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}
