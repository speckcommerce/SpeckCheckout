<?php

namespace SpeckCheckout\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;

abstract class AbstractCheckoutStageController extends AbstractActionController
{
    public function setEventManager(EventManagerInterface $events)
    {
        parent::setEventManager($events);

        // Setup a listener to redirect back to the cart if there is nothing in the basket
        $events->attach('dispatch', function (MvcEvent $e) {
            $sm = $e->getApplication()->getServiceManager();

            // Get the session cart
            $sessionCart = $sm->get('SpeckCart\Service\CartService')
                ->getSessionCart(false);

            // If it is set and has items do nothing
            if($sessionCart && $sessionCart->count() > 0) {
                return;
            }

            // There is an empty cart so redirect to the cart route
            return $e->getTarget()
                ->redirect()
                ->toRoute($sm->get('Config')['speck-checkout']['empty_cart_redirect_route']);
        }, 100);
    }
}
