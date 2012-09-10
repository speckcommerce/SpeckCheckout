<?php

namespace SpeckCheckout\Entity\Order;

class Order
{
    protected $orderId;
    protected $cartId;
    protected $checkoutId;

    public function getOrderId()
    {
        return $this->orderId;
    }

    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
        return $this;
    }

    public function getCartId()
    {
        return $this->cartId;
    }

    public function setCartId($cartId)
    {
        $this->cartId = $cartId;
        return $this;
    }

    public function getCheckoutId()
    {
        return $this->checkoutId;
    }

    public function setCheckoutId($checkoutId)
    {
        $this->checkoutId = $checkoutId;
        return $this;
    }
}
