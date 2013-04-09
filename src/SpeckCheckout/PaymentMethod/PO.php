<?php

namespace SpeckCheckout\PaymentMethod;

use SpeckCheckout\Strategy\Step\PaymentInformation;

use Zend\Form\Form;
use Zend\Http\PhpEnvironment\Response;

class PO extends AbstractOnSitePaymentMethod
{
    protected $paymentMethod = 'po';
    protected $displayName = 'Purchase Order';
    protected $viewPartialName = 'speck-checkout/payment/partial/po';

    public function getForm()
    {
        $form = new Form;
        $form->add(array(
            'name' => 'po_number',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => 'PO Number',
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
