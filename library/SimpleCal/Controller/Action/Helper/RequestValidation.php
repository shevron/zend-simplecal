<?php

class SimpleCal_Controller_Action_Helper_RequestValidation extends Zend_Controller_Action_Helper_Abstract
{
    private $validationPassword;
    
    public function init() {
          $this->validationPassword = $this->getActionController()->getHelper('jqEmail')->getValidationPassword();
    }
    
    public function direct($password)
    {
        return ((isset($password) != false) && ($password == $this->validationPassword));
    }
}
