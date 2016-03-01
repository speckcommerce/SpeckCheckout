<?php

namespace SpeckCheckout\Controller;

use Zend\View\Model\ViewModel;

class CheckoutController extends AbstractCheckoutStageController
{
    protected $checkoutService;

    public function indexAction()
    {
        $strategy = $this->getCheckoutService()->getCheckoutStrategy();
        $strategy->setStarted(true);

        $entryPoint = $this->getCheckoutService()->getCheckoutCurrentStep();
        if ($entryPoint == null) {
            var_dump($this->getCheckoutService()->getCheckoutStrategy());
            return;
        }
        return $this->redirect()->toRoute($entryPoint['route']);
    }

    /**
     * @TODO REMOVE -- DEV ONLY
     */
    public function tempPopulateCartAction()
    {
        $cartsvc = $this->getServiceLocator()->get('SpeckCart\Service\CartService');

        $item = new \SpeckCart\Entity\CartItem(array(
            'description' => 'Widget',
            'price' => 0.99,
            'quantity' => 2,
            'added_time' => new \DateTime(),
            'tax' => 0.00
        ));

        $cartsvc->addItemToCart($item);
        die();
    }

    public function getCheckoutService()
    {
        if (!isset($this->checkoutService)) {
            $this->checkoutService = $this->getServiceLocator()->get('SpeckCheckout\Service\Checkout');
        }

        return $this->checkoutService;
    }

    public function setCheckoutService($service)
    {
        $this->checkoutService = $service;
    }
}
