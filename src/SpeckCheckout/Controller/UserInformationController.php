<?php

namespace SpeckCheckout\Controller;

use SpeckCheckout\Strategy\Step\UserInformation;

use Zend\Http\PhpEnvironment\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Stdlib\Parameters;
use Zend\View\Model\ViewModel;

class UserInformationController extends AbstractActionController
{
    public function indexAction()
    {
        $form = $this->getRegisterForm();

        //$prg = $this->prg('checkout/user-information');
        //if ($prg instanceof Response) {
        //    //return $prg;
        //} else if ($prg === false) {
        //    return array(
        //        'form' => $form,
        //    );
        //}

        if (!$this->getRequest()->isPost()) {
             return array('form' => $form);
        }

        $zfcuser  = $form->get('zfcuser')->setData($_POST['zfcuser']);
        $shipping = $form->get('shipping')->setData($_POST['shipping']);
        $billing  = $form->get('billing')->setData($_POST['billing']);

        $valid1 = $zfcuser->isValid();
        $valid2 = $shipping->isValid();
        $valid3 = $billing->isValid();

        $valid = $valid1 && $valid2 && $valid3;

        if (!$valid) {
            return array(
                'form' => $form,
            );
        }

        $user = $this->getServiceLocator()->get('zfcuser_user_service')->register($zfcuser->getData());

        // TODO
        $contact = array('name' => 'TODO', 'display_name' => 'TODO');
        $contactService = $this->getServiceLocator()->get('SpeckContact\Service\ContactService');
        $contact = $contactService->createContact($contact);
        $ship = $contactService->createAddress($shipping->getData(), $contact->getContactId());
        $bill = $contactService->createAddress($billing->getData(), $contact->getContactId());

        $userContactService = $this->getServiceLocator()->get('SpeckUserContact\Service\UserContact');
        $userContactService->link($user->getId(), $contact->getContactId());

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

        $initialPost = $this->getRequest()->getPost();

        $post['identity'] = $user->getEmail();
        $post['credential'] = $initialPost['zfcuser']['password'];
        $post['redirect'] = $this->url()->fromRoute('checkout');

        $this->getRequest()->setPost(new Parameters($post));

        return $this->forward()->dispatch('zfcuser', array(
            'action'   => 'authenticate',
        ));
    }

    public function getRegisterForm()
    {
        $userForm = $this->getServiceLocator()->get('zfcuser_register_form');
        $userForm->setName('zfcuser');
        $userForm->remove('submit');

        $shippingAddressForm = $this->getServiceLocator()->get('SpeckAddress\Form\Address');
        $shippingAddressForm->setName('shipping')
            ->setInputFilter($this->getServiceLocator()->get('SpeckAddress\Form\AddressFilter'));

        $billingAddressForm = $this->getServiceLocator()->get('SpeckAddress\Form\Address');
        $billingAddressForm->setName('billing')
            ->setInputFilter($this->getServiceLocator()->get('SpeckAddress\Form\AddressFilter'));

        $form = new \Zend\Form\Form;

        $form->add($userForm)
            ->add($shippingAddressForm)
            ->add($billingAddressForm);

        return $form;
    }
}
