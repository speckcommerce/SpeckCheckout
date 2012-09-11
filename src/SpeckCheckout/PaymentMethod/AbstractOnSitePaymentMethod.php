<?php

namespace SpeckCheckout\PaymentMethod;

abstract class AbstractOnSitePaymentMethod extends AbstractPaymentMethod
{
    protected $form;

    public function getForm()
    {
        return $this->form;
    }

    public function setForm($form)
    {
        $this->form = $form;
        return $this;
    }
}
