<?php

namespace SpeckCheckout\Controller;

use SpeckCheckout\Strategy\Step\PaymentInformation;

use Zend\Http\PhpEnvironment\Response;
use Zend\View\Model\ViewModel;

class PaymentController extends AbstractCheckoutStageController
{
    public function indexAction()
    {
        $checkoutService = $this->getServiceLocator()->get('SpeckCheckout\Service\Checkout');
        $options         = $checkoutService->getOptions();
        $paymentMethods  = $options->getPaymentMethods();

        $paymentMethod = $checkoutService->getCheckoutStrategy()->getPaymentMethod();
        $methodString = ($paymentMethod ? $paymentMethod->getPaymentMethod() : null );

        $methodForm = new \Zend\Form\Form;
        foreach ($paymentMethods as $i) {
            $valueOptions[$i->getPaymentMethod()] = array(
                'value'    => $i->getPaymentMethod(),
                'label'    => $i->getDisplayName(),
                'selected' => ($i->getPaymentMethod() === $methodString),
            );
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
        $method = ($request->getQuery()->get('method') ?: $request->getPost()->get('method'));
        if($method && array_key_exists($method, $paymentMethods)) {
            $paymentMethod = $paymentMethods[$method];
        }else{
            throw new \Exception('Invalid payment method');
        }

        return $paymentMethod->getActionResponse($this);
    }
}
