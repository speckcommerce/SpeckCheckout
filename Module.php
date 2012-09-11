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
            'factories' => array(
                'SpeckCheckout\Service\Checkout' => function($sm) {
                    $service = new Service\Checkout;
                    $service->setOptions($sm->get('SpeckCheckout\Options\CheckoutOptions'));
                    return $service;
                },

                'SpeckCheckout\Options\CheckoutOptions' => function($sm) {
                    $config = $sm->get('application')->getConfig();
                    $options = new Options\CheckoutOptions($config['speck-checkout']);
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
