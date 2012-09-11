<?php

namespace SpeckCheckout\PaymentMethod;

use SpeckCheckout\Strategy\Step\PaymentInformation;

class Fax extends AbstractOnSitePaymentMethod
{
    protected $paymentMethod = 'fax';
    protected $displayName = 'Fax';
    protected $viewPartialName = 'speck-checkout/payment/partial/fax';

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
