<?php

namespace SpeckCheckout\PaymentMethod;

use SpeckCheckout\Strategy\Step\PaymentInformation;

class Check extends AbstractOnSitePaymentMethod
{
    protected $paymentMethod = 'check';

    protected $displayName = 'Check / Money Order';

    protected $viewPartialName = 'speck-checkout/payment/partial/check';

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
