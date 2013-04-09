<?php

namespace SpeckCheckout\PaymentMethod;

use SpeckCheckout\Strategy\Step\PaymentInformation;

use Zend\Form\Form;
use Zend\Http\PhpEnvironment\Response;

class Phone extends AbstractOnSitePaymentMethod
{
    protected $paymentMethod = 'phone';
    protected $displayName = 'Phone';
    protected $viewPartialName = 'speck-checkout/payment/partial/phone';

    public function getForm()
    {
        $form = new Form;
        $form->add(array(
            'name' => 'phone',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => 'Phone Number',
            ),
        ));

        return $form;
    }

    public function getActionResponse($controller)
    {
        $checkoutService = $controller->getServiceLocator()->get('SpeckCheckout\Service\Checkout');
        $strategy = $checkoutService->getCheckoutStrategy();
        $strategy->setPaymentMethod($this);

        $form = $this->getForm();

        $url = $controller->url()->fromRoute('checkout/payment/main') . '?method=' . $this->paymentMethod;

        $prg = $controller->prg($url, true);

        if ($prg instanceof Response) {
            return $prg;
        } else if ($prg === false) {
            return array('form' => $form, 'method' => $this->paymentMethod);
        }

        $this->data = $prg;

        foreach ($strategy->getSteps() as $step) {
            if ($step instanceof PaymentInformation) {
                $step->setComplete(true);
                break;
            }
        }

        return $controller->redirect()->toRoute('checkout');
    }
}
