<?php

namespace SpeckCheckout\Controller;

use SpeckCheckout\Strategy\Step\PaymentInformation;

use Zend\Http\PhpEnvironment\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class PaymentController extends AbstractActionController
{
    public function indexAction()
    {
        $checkoutService = $this->getServiceLocator()->get('SpeckCheckout\Service\Checkout');
        $options         = $checkoutService->getOptions();
        $paymentMethods  = $options->getPaymentMethods();

        $methodForm = new \Zend\Form\Form;
        foreach ($paymentMethods as $i) {
            $valueOptions[$i->getPaymentMethod()] = $i->getDisplayName();
        }

        $methodForm->add(array(
            'name' => 'method',
            'type' => 'Zend\Form\Element\Radio',
            'options' => array(
                'label' => 'Payment Method',
                'value_options' => $valueOptions,
            ),
        ));

        return array('form' => $methodForm);
    }

    public function paymentAction()
    {
        $checkoutService = $this->getServiceLocator()->get('SpeckCheckout\Service\Checkout');
        $options         = $checkoutService->getOptions();
        $paymentMethods  = $options->getPaymentMethods();
        $request         = $this->getRequest();

        $paymentMethod = null;
        foreach ($paymentMethods as $name => $method) {
            if ($name === $request->getQuery()->get('method')) {
                $paymentMethod = $method;
                break;
            }
        }

        if (!$paymentMethod) {
            throw new \Exception('Invalid payment method');
        }

        return $paymentMethod->getActionResponse($this);
    }
}
