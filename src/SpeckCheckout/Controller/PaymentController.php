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
        $paymentMethod = $checkoutService->getCheckoutStrategy()->getPaymentMethod();
        if ($paymentMethod) {
            $methodForm->get('method')->setValue($paymentMethod->getPaymentMethod());
        }

        return array('form' => $methodForm);
    }

    public function paymentAction()
    {
        $checkoutService = $this->getServiceLocator()->get('SpeckCheckout\Service\Checkout');
        $options         = $checkoutService->getOptions();
        $paymentMethods  = $options->getPaymentMethods();
        $request         = $this->getRequest();

        $paymentMethod = null;
        $method = ($request->getQuery()->get('method') ?: $request->getPost()->get('method'));
        if($method && array_key_exists($method, $paymentMethods)) {
            $paymentMethod = $paymentMethods[$method];
        }else{
            throw new \Exception('Invalid payment method');
        }

        return $paymentMethod->getActionResponse($this);
    }
}
