<?php

namespace SpeckCheckout\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class OrderReviewController extends AbstractActionController
{
    protected $checkoutService;
    protected $cartService;

    public function indexAction()
    {
        $cart = $this->getCartService()->getSessionCart();
        $checkout = $this->getCheckoutService()->getCheckoutStrategy();

        return array(
            'cart' => $cart,
            'checkout' => $checkout,
        );
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

    public function getCartService()
    {
        if (!isset($this->cartService)) {
            $this->cartService = $this->getServiceLocator()->get('SpeckCart\Service\CartService');
        }

        return $this->cartService;
    }

    public function setCartService($service)
    {
        $this->cartService = $service;
    }
}
