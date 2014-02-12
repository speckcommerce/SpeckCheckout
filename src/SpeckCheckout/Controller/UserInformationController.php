<?php

namespace SpeckCheckout\Controller;

use SpeckCheckout\Strategy\Step\UserInformation;

use Zend\Http\PhpEnvironment\Response;
use Zend\Stdlib\Parameters;
use Zend\View\Model\ViewModel;

class UserInformationController extends AbstractCheckoutStageController
{
    public function indexAction()
    {
        if ($this->zfcuserauthentication()->hasIdentity()) {
            return $this->redirect()->toRoute('checkout/user-information/addresses');
        }

        $form = $this->getRegisterForm();

        $prg = $this->prg('checkout/user-information');
        if ($prg instanceof Response) {
            return $prg;
        } else if ($prg === false) {
            return array(
                'form' => $form,
            );
        }

        $shipping = $form->get('shipping')->setData($prg['shipping']);
        $billing  = $form->get('billing')->setData($prg['billing']);
        $zfcuser  = $form->get('zfcuser')->setData($prg['zfcuser']);

        $eventData = array('form' => $form, 'prg' => $prg);
        $responses = $this->getEventManager()->trigger(
            __FUNCTION__ . '.validate', $this, $eventData
        );
        foreach($responses as $response) {
            $prg = isset($response['prg']) ? $response['prg'] : $prg;
        }

        $valid = ($shipping->isValid() && $billing->isValid() && $zfcuser->isValid());
        if (!$valid) {
            return array('form' => $form);
        }

        $user = $this->getServiceLocator()->get('zfcuser_user_service')->register($zfcuser->getData());

        // TODO: contact name
        $userAddressService = $this->getServiceLocator()->get('SpeckUserAddress\Service\UserAddress');
        $ship = $userAddressService->create($shipping->getData(), $user->getId());
        $bill = $userAddressService->create($billing->getData(), $user->getId());

        $checkoutService = $this->getServiceLocator()->get('SpeckCheckout\Service\Checkout');
        $strategy = $checkoutService->getCheckoutStrategy();
        $strategy->setShippingAddress($ship);
        $strategy->setBillingAddress($bill);
        $strategy->setEmailAddress($user->getEmail());

        foreach ($strategy->getSteps() as $step) {
            if ($step instanceof UserInformation) {
                $step->setComplete(true);
                break;
            }
        }

        $post['identity']   = $user->getEmail();
        $post['credential'] = $prg['zfcuser']['password'];
        $post['redirect']   = $this->url()->fromRoute('checkout');

        $this->getRequest()->setPost(new Parameters($post));

        return $this->forward()->dispatch('zfcuser', array(
            'action' => 'authenticate',
        ));
    }

    public function pickAddressesAction()
    {
        $userAddressService = $this->getServiceLocator()->get('SpeckUserAddress\Service\UserAddress');

        $prg = $this->prg('checkout/user-information/addresses');
        if ($prg instanceof Response) {
            return $prg;
        }

        $addresses = $userAddressService->getAddresses()->toArray();

        $addressesArray = array();
        foreach ($addresses as $a) {
            $addressesArray[$a['address_id']] = $a;
        }

        $shippingAddressForm = $this->getServiceLocator()->get('SpeckAddress\Form\Address');
        $shippingAddressForm->setName('shipping')->setWrapElements(true)
            ->setInputFilter($this->getServiceLocator()->get('SpeckAddress\Form\AddressFilter'));

        $billingAddressForm = $this->getServiceLocator()->get('SpeckAddress\Form\Address');
        $billingAddressForm->setName('billing')->setWrapElements(true)
            ->setInputFilter($this->getServiceLocator()->get('SpeckAddress\Form\AddressFilter'));

        $form = new \Zend\Form\Form;

        $form->add($shippingAddressForm)
             ->add($billingAddressForm);

        $checkoutService = $this->getServiceLocator()->get('SpeckCheckout\Service\Checkout');

        if ($prg === false) {

            $strategy = $checkoutService->getCheckoutStrategy();
            $return = array(
                'addresses' => $addressesArray,
                'form'      => $form,
            );
            if ($strategy->getShippingAddress()) {
                $return['ship_prefill'] = $strategy->getShippingAddress()->getAddressId();
            }
            if ($strategy->getBillingAddress()) {
                $return['bill_prefill'] = $strategy->getBillingAddress()->getAddressId();
            }
            return $return;
        }

        $shipping = $form->get('shipping');
        $billing  = $form->get('billing');

        $shipping->setData(isset($prg['shipping']) ? $prg['shipping'] : array());
        $billing->setData(isset($prg['billing'])   ? $prg['billing']  : array());

        $eventData = array('form' => $form, 'prg' => $prg);
        $responses = $this->getEventManager()->trigger(
            __FUNCTION__ . '.validate', $this, $eventData
        );
        foreach($responses as $response) {
            $prg = isset($response['prg']) ? $response['prg'] : $prg;
        }


        $shippingAddressId = isset($prg['shipping_address_id']) ? $prg['shipping_address_id'] : 0;
        $billingAddressId  = isset($prg['billing_address_id'])  ? $prg['billing_address_id']  : 0;


        $valid1 = ($shippingAddressId != 0) ? true : $shipping->isValid();
        $valid2 = ($billingAddressId  != 0) ? true : $billing->isValid();
        $valid  = $valid1 && $valid2;

        if (!$valid) {
            return array(
                'ship_prefill' => $shippingAddressId,
                'bill_prefill' => $billingAddressId,
                'addresses' => $addressesArray,
                'form'      => $form,
            );
        }

        $addressService     = $this->getServiceLocator()->get('SpeckAddress\Service\Address');
        $userAddressService = $this->getServiceLocator()->get('SpeckUserAddress\Service\UserAddress');

        $user = $this->zfcUserAuthentication()->getIdentity();

        if ($shippingAddressId == 0) {
            $ship = $userAddressService->create($shipping->getData());
        } else {
            $ship = $addressService->findById($shippingAddressId);
        }

        if ($billingAddressId == 0) {
            $bill = $userAddressService->create($billing->getData());
        } else {
            $bill = $addressService->findById($billingAddressId);
        }

        $strategy = $checkoutService->getCheckoutStrategy();
        $strategy->setShippingAddress($ship);
        $strategy->setBillingAddress($bill);
        $strategy->setEmailAddress($user->getEmail());

        foreach ($strategy->getSteps() as $step) {
            if ($step instanceof UserInformation) {
                $step->setComplete(true);
                break;
            }
        }

        return $this->redirect()->toRoute('checkout');
    }

    public function getRegisterForm()
    {
        $userForm = $this->getServiceLocator()->get('zfcuser_register_form');
        $userForm->setName('zfcuser')->setWrapElements(true);
        $userForm->remove('submit');

        $shippingAddressForm = $this->getServiceLocator()->get('SpeckAddress\Form\Address');
        $shippingAddressForm->setName('shipping')->setWrapElements(true)
            ->setInputFilter($this->getServiceLocator()->get('SpeckAddress\Form\AddressFilter'));

        $billingAddressForm = $this->getServiceLocator()->get('SpeckAddress\Form\Address');
        $billingAddressForm->setName('billing')->setWrapElements(true)
            ->setInputFilter($this->getServiceLocator()->get('SpeckAddress\Form\AddressFilter'));

        $form = new \Zend\Form\Form;

        $form->add($userForm)
            ->add($shippingAddressForm)
            ->add($billingAddressForm);

        return $form;
    }
}
