<?php

namespace SpeckCheckout\PaymentMethod;

use SpeckCheckout\Strategy\Step\PaymentInformation;

class Quote extends AbstractOnSitePaymentMethod
{
    protected $paymentMethod = 'quote';
    protected $displayName = 'Quote';
    protected $viewPartialName = 'speck-checkout/payment/partial/quote';

    public function getActionResponse($controller)
    {
        $checkoutService = $controller->getServiceLocator()->get('SpeckCheckout\Service\Checkout');
        $strategy = $checkoutService->getCheckoutStrategy();
        $strategy->setPaymentMethod($this);

        foreach ($strategy->getSteps() as $step) {
            if ($step instanceof PaymentInformation) {
                $step->setComplete(true);
                break;
            }
        }

        return $controller->redirect()->toRoute('checkout');
    }
}
